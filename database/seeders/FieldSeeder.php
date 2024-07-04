<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Field;
use App\Models\DailySheet;

class FieldSeeder extends Seeder
{
    public function run()
    {
        $dailySheetPersonal = DailySheet::where('name', 'Personal')->first();
        $dailySheetMaquinaria = DailySheet::where('name', 'Maquinaria')->first();

        $fieldsPersonal = [
            [
                'name' => 'Nombre',
                'description' => 'Nombre del personal',
                'field_type' => 'text',
                'daily_sheet_id' => $dailySheetPersonal->id,
            ],
            [
                'name' => 'Apellido',
                'description' => 'Apellido del personal',
                'field_type' => 'text',
                'daily_sheet_id' => $dailySheetPersonal->id,
            ]
        ];

        $fieldsMaquinaria = [
            [
                'name' => 'Nombre',
                'description' => 'Nombre de la maquinaria',
                'field_type' => 'text',
                'daily_sheet_id' => $dailySheetMaquinaria->id,
            ],
            [
                'name' => 'Modelo',
                'description' => 'Modelo de la maquinaria',
                'field_type' => 'text',
                'daily_sheet_id' => $dailySheetMaquinaria->id,
            ]
        ];

        foreach ($fieldsPersonal as $field) {
            Field::create($field);
        }

        foreach ($fieldsMaquinaria as $field) {
            Field::create($field);
        }
    }
}

