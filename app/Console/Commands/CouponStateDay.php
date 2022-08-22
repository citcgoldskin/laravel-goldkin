<?php

namespace App\Console\Commands;

use App\Service\CouponService;
use Illuminate\Console\Command;

class CouponStateDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couponstate:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Coupon Usage State Daily!';

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
        CouponService::updateCouponUsageOld();
        return 0;
    }
}
