<?php

namespace App\Console\Commands;

use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Console\Command;

class QuestionReserveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'question_reserve:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $now = Carbon::now()->format('Y-m-d H:i:s');

        // 公開予約
        $public_questions_count = Question::whereNotNull('que_public_at')
            ->where('que_public_at', '<', $now)
            ->where('que_public', config('const.question_category_public.no_public'))
            ->where('que_status', config('const.question_status.register'))
            ->get()
            ->count();

        $public_questions = Question::whereNotNull('que_public_at');
        $public_questions->where('que_public_at', '<', $now);
        $public_questions->where('que_public', config('const.question_category_public.no_public'));
        $public_questions->where('que_status', config('const.question_status.register'));
        $public_questions->update([
            'que_public'=>config('const.question_category_public.public')
        ]);

        echo '公開予約：'.$public_questions_count."件\n";

        // 変更予約
        $update_questions = Question::whereNotNull('que_update_at')
            ->where('que_update_at', '<', $now)
            ->where('que_status', config('const.question_status.register'))
            ->get();

        $update_questions_count = $update_questions->count();

        if ($update_questions->count() > 0) {
            foreach ($update_questions as $obj_question) {
                $update_data = json_decode($obj_question->que_update_data, true);
                $obj_question->que_qc_id = $update_data['que_qc_id'];
                $obj_question->que_ask = $update_data['que_ask'];
                $obj_question->que_answer = $update_data['que_answer'];
                $obj_question->que_public = config('const.question_category_public.public');
                $obj_question->que_public_at = $now;
                $obj_question->que_update_at = null;
                $obj_question->que_update_data = null;
                $obj_question->save();
            }
        }

        echo '変更予約：'.$update_questions_count."件\n";

        // 削除予約
        $delete_questions_count = Question::whereNotNull('que_delete_at')
            ->where('que_delete_at', '<', $now)
            ->where('que_status', config('const.question_status.register'))
            ->get()
            ->count();

        $delete_questions = Question::whereNotNull('que_delete_at');
        $delete_questions->where('que_delete_at', '<', $now);
        $delete_questions->where('que_status', config('const.question_status.register'));
        $delete_questions->delete();

        echo '削除予約：'.$delete_questions_count."件\n";

        return Command::SUCCESS;
    }
}
