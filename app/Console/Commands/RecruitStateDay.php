<?php

namespace App\Console\Commands;

use App\Service\KeijibannService;
use Illuminate\Console\Command;

class RecruitStateDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recruitstate:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Recruit State Daily!';

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
        KeijibannService::updateRecruitOldState();
        return 0;
    }
}
