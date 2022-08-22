<?php

namespace Database\Seeders;

use App\Imports\BankDataImport;
use Illuminate\Database\Seeder;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bank_data_file = 'database/excel/ginkositen.xlsx';
        $import = new BankDataImport();
        Excel::import($import, $bank_data_file);
        $this->command->info('Bank table seeded!');

    }
}
