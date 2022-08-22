<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\LessonService;

class Lesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lesson:setstate
                            {--id=* : The Id of the lesson}
                            {--state= : 0: draft, 1:private, 2:check, 3:public, 4:reject, 5:delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will be set state for a lesson.';

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
        $lesson_ids = $this->option('id');
        $state = $this->option('state');

        if ($state < config('const.lesson_state.draft') || $state > config('const.lesson_state.delete'))
            $state = config('const.lesson_state.draft');

        foreach ($lesson_ids as $k => $v) {
            LessonService::updateLessonState($v, $state);
        }
        $this->info('The command was successful!');
        return 0;
    }
}
