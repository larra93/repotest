<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DailySheet;

class DailySheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dailySheets = [
            [
                'name' => 'Personal',
                'contract_id' => 1,
            ],
            [
                'name' => 'Maquinaria',
                'contract_id' => 1,
            ]
        ];

        foreach ($dailySheets as $dailySheet) {
            DailySheet::create($dailySheet);
        }
    }
    
}
