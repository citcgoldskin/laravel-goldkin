<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Service\LessonService;
use App\Service\SettingService;
use App\Service\TalkroomService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TaskByMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskbyminute:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'my task schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curDate = date('Y-m-d');
        Log::info("\ncurDate:".$curDate."\n");

        //get lessons for start needed and inform with talkroom.
        $startTime = date('H:i:00' );
        Log::info("\nstartTime:".$startTime."\n");
        $schObjs = LessonService::getSchedulesFromStartTime($curDate, $startTime, config('const.schedule_state.reserve'));

        $msg = 'レッスン開始時刻となりました。<br>センパイと合流できたら、スタートボタンをタップしてください。';
        foreach( $schObjs as $k => $v ) {
            if (is_null($v['lesson_request']) || is_null($v['lesson_request']['lesson']))
                continue;
            $senpai_id = $v['lesson_request']['lesson']['lesson_senpai_id'];
            $kouhai_id = $v['lesson_request']['lr_user_id'];
            Log::info("time reached:".$msg);
            TalkroomService::saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg);
            TalkroomService::saveKouhaiBtnCommon($kouhai_id, $senpai_id, TalkroomService::Kouhai_Btn_Start, $v['lrs_id']);
        }

        //get lessons for xx minutes passed of start needed and inform with talkroom (5min)
        $lsStartAlarmTime = SettingService::getSetting('lesson_start_alarm_time', 'int');
        $startAlarmTime = date('H:i:00', time() - $lsStartAlarmTime * 60 );
        $schObjs = LessonService::getSchedulesFromStartTime($curDate, $startAlarmTime, config('const.schedule_state.reserve'));

        foreach( $schObjs as $k => $v ) {
            if (is_null($v['lesson_request']) || is_null($v['lesson_request']['lesson']))
                continue;
            $senpai_id = $v['lesson_request']['lesson']['lesson_senpai_id'];
            $kouhai_id = $v['lesson_request']['lr_user_id'];
            TalkroomService::saveSenpaiSysPosShare($senpai_id, $kouhai_id, $lsStartAlarmTime, $v['lrs_id']);
            TalkroomService::saveKouhaiSysPosShare($kouhai_id, $senpai_id, $lsStartAlarmTime, $v['lrs_id']);
        }

        //センパイが現れない場合は、キャンセル申請を行ってください。(15min)
        $cancelAlarmTime = date('H:i:00', time() - config('const.lesson_cancel_alarm_time') * 60 );
        Log::info("\nif passed 15 mins _________:".$cancelAlarmTime."\n");
        $schObjs = LessonService::getSchedulesFromStartTime($curDate, $cancelAlarmTime, config('const.schedule_state.reserve'));
        foreach( $schObjs as $k => $v ) {
            if (is_null($v['lesson_request']) || is_null($v['lesson_request']['lesson']))
                continue;
            $senpai_id = $v['lesson_request']['lesson']['lesson_senpai_id'];
            $kouhai_id = $v['lesson_request']['lr_user_id'];

            Log::info("\n passed 15 mins \n");
            TalkroomService::saveSenpaiSysCancelMoney($senpai_id, $kouhai_id);
            TalkroomService::saveSenpaiBtnCommon($senpai_id, $kouhai_id, TalkroomService::Senpai_Btn_PosCancel, $v['lrs_id']);

            TalkroomService::saveKouhaiSysCancelMoney($kouhai_id, $senpai_id);
            TalkroomService::saveKouhaiBtnCommon($kouhai_id, $senpai_id, TalkroomService::Kouhai_Btn_PosCancel, $v['lrs_id']);
        }

        //get lessons for cancel of start and inform with talkroom (60min)
        $lsCancelTime = SettingService::getSetting('lesson_cancel_time', 'int');
        $cancelTime = date('H:i:00', time() - $lsCancelTime * 60 );
        $schObjs = LessonService::getSchedulesFromStartTime($curDate, $cancelTime, config('const.schedule_state.reserve'));

        $msg = 'レッスン開始時刻から'.$lsCancelTime.'分経過しました。<br><br>レッスンのスタートおよびキャンセルが行われなかったため、このレッスンは実施扱いとなりました。';
        foreach( $schObjs as $k => $v ) {
            if (is_null($v['lesson_request']) || is_null($v['lesson_request']['lesson']))
                continue;
            $senpai_id = $v['lesson_request']['lesson']['lesson_senpai_id'];
            $kouhai_id = $v['lesson_request']['lr_user_id'];
            TalkroomService::saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg);
            LessonService::updateScheduleState( $v['lrs_id'], config('const.schedule_state.cancel_system'));
        }

        //get finished lessons and inform with talkroom
        $schObjs = LessonService::getSchedules(config('const.schedule_state.start'));
        $curTime = time();
        Log::info("\ncurTime:".$curTime."\n");
        foreach ( $schObjs as $k => $v ) {
            if (is_null($v['lrs_start_date']))
                continue;
            $lessonTime = strtotime($v['lrs_end_time']) - strtotime($v['lrs_start_time']);
            $startTime = strtotime($v['lrs_start_date']);
            $senpai_id = $v['lesson_request']['lesson']['lesson_senpai_id'];
            $kouhai_id = $v['lesson_request']['lr_user_id'];
            $endTime = $startTime + $lessonTime;
            Log::info("\nendTime:".$endTime."\n");
            if ( $endTime > $curTime )
                continue;

            LessonService::updateScheduleState( $v['lrs_id'], config('const.schedule_state.complete'));

            $obj_kouhai = User::find($kouhai_id);
            $obj_senpai = User::find($senpai_id);

            $msg = $obj_senpai->name.'さん。<br>本日のレッスンお疲れ様でした。<br>もしよろしければ'.$obj_kouhai->name.'さんの評価をお願いします。<br>※評価した内容は相手側には公開されません。';
            TalkroomService::saveSenpaiSysMsg($senpai_id, $kouhai_id, $msg);

            $msg = $obj_kouhai->name.'さん。<br>本日のレッスンお疲れ様でした。<br>もしよろしければ'.$obj_senpai->name.'センパイの評価をお願いします。<br>※評価した内容は相手側には公開されません。';
            TalkroomService::saveKouhaiSysMsg($kouhai_id, $senpai_id, $msg);

            TalkroomService::saveSenpaiBtnEvalution($senpai_id, $kouhai_id, $v['lesson_request']['lr_id'], $v['lrs_id']);
            TalkroomService::saveKouhaiBtnEvalution($kouhai_id, $senpai_id, $v['lesson_request']['lr_id'], $v['lrs_id']);
        }

        //get lesson passed of until confirm date and cancel it.
        $schObjs = LessonService::getSchedulesPassedUntilConfirm($curDate);
        foreach ( $schObjs as $k => $v ) {
            LessonService::updateScheduleState( $v['lrs_id'], config('const.schedule_state.cancel_system'));
        }

        //

        return 0;
    }
}
