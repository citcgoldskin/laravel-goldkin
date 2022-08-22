<?php

namespace App\Console\Commands;

use App\Imports\AreaTableImport;
use App\Models\Area;
use App\Models\User;
use App\Service\SquarePaymentService;
use App\Service\UserService;
use Illuminate\Console\Command;
use DB;
class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:print';

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

        $square_payment = new SquarePaymentService();

        $obj_user = User::find(12);
        //Create Customer
        //dd($square_payment->createCustomer($obj_user));

        //Get Customers
        //dd($square_payment->getCustomerList());


        //dd(UserService::generateCustomerID());

        /*$this->info('START - Area CSV Import');

        DB::table('areas')->truncate();
        $csv_file = 'database/sql/csv/area.csv';
        $result_count = AreaTableImport::run($csv_file);

        $this->info('END - '. $result_count .' records Imported');*/


        $pref_areas = Area::where('area_deep', 2)
            ->pluck('area_region', 'area_id');

        Area::where('area_deep', 3)
            ->chunk(100, function ($areas) use($pref_areas) {
                foreach ($areas as $area){
                    $area->area_region = $pref_areas[$area->area_pref];
                    $area->save();
                    $this->info('Area Name: '. $area .' Region ID:' . $pref_areas[$area->area_pref]);
                }
        });
        return 0;
    }
}
