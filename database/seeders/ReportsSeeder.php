<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
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
                "Nome" => 'Ponte Rudavoi',
                "Frequenza" => 3,
                "Periodo" => 1,
                "isJSON" => 0,
                "isCSV" => 1,
                "isXML" => 1,
                "codStruttura" => 1,
            ],
            (object) [
                "id" => 2,
                "Nome" => 'Ponte degli Angeli',
                "Frequenza" => 6,
                "Periodo" => 2,
                "isJSON" => 1,
                "isCSV" => 1,
                "isXML" => 1,
                "codStruttura" => 2,
            ],
            (object) [
                "id" => 3,
                "Nome" => 'Ponte Rudavoi',
                "Frequenza" => 1,
                "Periodo" => 2,
                "isJSON" => 0,
                "isCSV" => 1,
                "isXML" => 0,
                "codStruttura" => 1,
            ],
            (object) [
                "id" => 4,
                "Nome" => 'Ponte degli Angeli',
                "Frequenza" => 10,
                "Periodo" => 0,
                "isJSON" => 1,
                "isCSV" => 1,
                "isXML" => 0,
                "codStruttura" => 2,
            ],
            (object) [
                "id" => 5,
                "Nome" => 'Ponte Rudavoi Est',
                "Frequenza" => 5,
                "Periodo" => 1,
                "isJSON" => 0,
                "isCSV" => 1,
                "isXML" => 1,
                "codStruttura" => 1,
            ],
            (object) [
                "id" => 6,
                "Nome" => 'Ponte Rudavoi Ovest',
                "Frequenza" => 9,
                "Periodo" => 0,
                "isJSON" => 0,
                "isCSV" => 0,
                "isXML" => 1,
                "codStruttura" => 1,
            ],

        ];

        $formati = ['json', 'csv', 'xml'];

        foreach ($reports as $key => $report) {
            DB::table('Reports')->insert([
                "id" => $report->id,
                "Nome" => $report->Nome,
                "Frequenza" => $report->Frequenza,
                "Periodo" => $report->Periodo,
                "isJSON" => $report->isJSON,
                "isCSV" => $report->isCSV,
                "isXML" => $report->isXML,
                "DataOra" => now(),
                "codStruttura" => $report->codStruttura,
            ]);
        }
    }
}