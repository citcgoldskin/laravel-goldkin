<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PunishmentAlertRequest;
use App\Http\Requests\Admin\PunishmentRequest;
use App\Mail\Admin\SendAlertEmail;
use App\Models\Punishment;
use App\Models\User;
use App\Service\AppealService;
use App\Service\PunishmentService;
use App\Service\UserService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use Session;
use DB;

class FraudPiroController extends AdminController
{
    public function index(Request $request)
    {
        $params = $request->all();
        $condition = Session::get('admin.fraud.piro', []);

        if (isset($params['search_params'])) {
            foreach ($params['search_params'] as $key=>$val) {
                $condition[$key] = $val;
            }
        }
        if (isset($params['clear_condition']) && $params['clear_condition'] == 1) {
            $condition = [
                "user_type" => null,
                "user_sex" => null,
                "user_id" => null,
                "nickname" => null
            ];
            $condition['order'] = config('const.user_sort_code.register_new');
        }

        if (isset($params['order']) && $params['order']) {
            $condition['order'] = $params['order'];
        } else {
            $condition['order'] = config('const.user_sort_code.register_new');
        }

        Session::put('admin.fraud.piro', $condition);


        $obj_users = PunishmentService::doSearch($condition)->paginate($this->per_page);
        return view('admin.fraud.piro.index', [
            'obj_users' => $obj_users,
            'search_params' => $condition
        ]);
    }

    public function detail(Request $request, Punishment $punishment)
    {
        $condition = [
            'from_date' => $punishment->before_punishment_at,
            'to_date' => $punishment->decided_at
        ];

        $appeal_users = AppealService::getAppealUserInfo($punishment->user, $condition);
        return view('admin.fraud.piro.detail', [
            'punishment' => $punishment,
            'appeal_users' => $appeal_users
        ]);
    }

    public function create(Request $request, User $user)
    {
        $condition = [
            'from_date' => $user->last_punishment_at,
            'to_date' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        $appeal_users = AppealService::getAppealUserInfo($user, $condition);
        $appealClasses = AppealService::getAppealClasses();

        $page_from = $request->input('page_from', '');

        $punishment_params = Session::get('admin.fraud.punishment', []);

        return view('admin.fraud.piro.create', [
            'own_user' => $user,
            'appClass' => $appealClasses,
            'page_from' => $page_from,
            'punishment_params' => $punishment_params,
            'appeal_users' => $appeal_users
        ]);
    }

    public function doCreateAlert(PunishmentRequest $request)
    {
        $punishment_params = $request->all();
        if (isset($punishment_params['_token'])) {
            unset($punishment_params['_token']);
        }
        Session::put('admin.fraud.punishment', $punishment_params);
        $params = $request->all();
        return redirect()->route('admin.fraud_piro.create_alert');
    }

    public function createAlert(Request $request)
    {
        $punishment_params = Session::get('admin.fraud.punishment', []);
        $obj_user = isset($punishment_params['user_id']) && $punishment_params['user_id'] ? User::find($punishment_params['user_id']) : null;
        return view('admin.fraud.piro.create_alert', [
            'obj_user'=>$obj_user,
            'punishment_params'=>$punishment_params
        ]);
    }

    public function confirm(Request $request) {
        $punishment_params = Session::get('admin.fraud.punishment', []);
        $obj_user = User::find($punishment_params['user_id']);
        return view('admin.fraud.piro.confirm', [
            'obj_user'=>$obj_user,
            'punishment_params'=>$punishment_params
        ]);
    }

    public function doConfirm(PunishmentAlertRequest $request)
    {
        $params = $request->all();
        $punishment_params = Session::get('admin.fraud.punishment', []);
        $punishment_params['alert_title'] = $params['alert_title'];
        $punishment_params['alert_text'] = $params['alert_text'];
        Session::put('admin.fraud.punishment', $punishment_params);
        return redirect()->route('admin.fraud_piro.confirm');
    }

    public function registerAlert(Request $request)
    {
        $contents = "操作が失敗しました。";
        $punishment_params = Session::get('admin.fraud.punishment', []);
        $user_id = $punishment_params['user_id'];
        $obj_user = User::find($user_id);
        if (PunishmentService::doRegister($punishment_params) && UserService::doPunishment($obj_user)) {
            try {
                $mail_obj = Mail::to($obj_user->email);
                /*$cc_mail_arr = [config('const.yamadazaidan_email')];
                $mail_obj->cc($cc_mail_arr);*/
                $mail_obj->send(new SendAlertEmail($punishment_params));
                $contents = "決定を行いました。";
            } catch (\Exception $exception) {
                Log::error("EMail Sending Failed");
            }
        }
        $modal_confrim_url = route('admin.fraud_report.index');
        if (isset($punishment_params['page_from']) && $punishment_params['page_from'] == "block") {
            $modal_confrim_url = route('admin.fraud_block.index');
        } else if(isset($punishment_params['page_from']) && $punishment_params['page_from'] == "profile") {
            $modal_confrim_url = route('admin.staff.detail')."/".$user_id;
        } else if(isset($punishment_params['page_from']) && $punishment_params['page_from'] == "lesson_history") { // レッスン履歴
            $modal_confrim_url = route('admin.staff.detail')."/".$user_id;
        }
        Session::forget('admin.fraud.punishment');

        return view('admin.layouts.modal_ok',[
            'contents'=>$contents,
            'modal_confrim_url'=>$modal_confrim_url
        ]);
    }

}
