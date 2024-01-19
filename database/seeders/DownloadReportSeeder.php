<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DownloadReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reports = [
            (object) [
                "id" => 1,
                "Nome" => 'Ponte Rudavoi_20230302',
            ],
            (object) [
                "id" => 2,
                "Nome" => 'Ponte degli Angeli_20230301',
            ],
            (object) [
                "id" => 3,
                "Nome" => 'Ponte Rudavoi_20230227',
            ],
            (object) [
                "id" => 4,
                "Nome" => 'Ponte degli Angeli_20230225',
            ],
            (object) [
                "id" => 5,
                "Nome" => 'Ponte Rudavoi Est_20230213',
            ],
            (object) [
                "id" => 6,
                "Nome" => 'Ponte Rudavoi Ovest_20230208',
            ],
        ];

        $altriReports = [
            (object) [
                "id" => 7,
                "Nome" => 'Ponte Rudavoi_20230102',
                'codReport' => 1,
            ],
            (object) [
                "id" => 8,
                "Nome" => 'Ponte degli Angeli_20230101',
                'codReport' => 2,
            ],
            (object) [
                "id" => 9,
                "Nome" => 'Ponte Rudavoi_20221227',
                'codReport' => 1,
            ],
            (object) [
                "id" => 10,
                "Nome" => 'Ponte degli Angeli_20221225',
                'codReport' => 2,
            ],
            (object) [
                "id" => 11,
                "Nome" => 'Ponte Rudavoi Est_20221213',
                'codReport' => 5,
            ],
            (object) [
                "id" => 12,
                "Nome" => 'Ponte Rudavoi Ovest_20221208',
                'codReport' => 6,
            ],
        ];

        $formati = ['json', 'csv', 'xml'];

        foreach ($reports as $key => $report) {
            foreach ($formati as $key2 => $formato) {
                DB::table('DownloadReport')->insert([
                    'Nome' => $report->Nome,
                    'codReport' => $report->id,
                    'isDownload' => 0,
                    'Formato' => $formato,
                ]);
            }
        }
        foreach ($altriReports as $key3 => $altroReport) {
            foreach ($formati as $key4 => $formato) {
                DB::table('DownloadReport')->insert([
                    'Nome' => $altroReport->Nome,
                    'codReport' => $altroReport->codReport,
                    'isDownload' => 0,
                    "Formato" => $formato,
                ]);
            }
        }
    }
}