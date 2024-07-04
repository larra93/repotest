<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Value;
use App\Models\Field;
use App\Models\DailySheet;

class ValueSeeder extends Seeder
{
    public function run()
    {
        $dailySheetPersonal = DailySheet::where('name', 'Personal')->first();
        $dailySheetMaquinaria = DailySheet::where('name', 'Maquinaria')->first();

        $fieldsPersonal = $dailySheetPersonal->fields;
        $fieldsMaquinaria = $dailySheetMaquinaria->fields;

        $valuesPersonal = [
            ['field_id' => $fieldsPersonal->where('name', 'Nombre')->first()->id, 'value' => 'John', 'row' => 1],
            ['field_id' => $fieldsPersonal->where('name', 'Apellido')->first()->id, 'value' => 'Doe', 'row' => 1],
            ['field_id' => $fieldsPersonal->where('name', 'Nombre')->first()->id, 'value' => 'Jane', 'row' => 2],
            ['field_id' => $fieldsPersonal->where('name', 'Apellido')->first()->id, 'value' => 'Smith', 'row' => 2]
        ];

        $valuesMaquinaria = [
            ['field_id' => $fieldsMaquinaria->where('name', 'Nombre')->first()->id, 'value' => 'Excavadora', 'row' => 1],
            ['field_id' => $fieldsMaquinaria->where('name', 'Modelo')->first()->id, 'value' => 'CAT320', 'row' => 1],
            ['field_id' => $fieldsMaquinaria->where('name', 'Nombre')->first()->id, 'value' => 'Bulldozer', 'row' => 2],
            ['field_id' => $fieldsMaquinaria->where('name', 'Modelo')->first()->id, 'value' => 'D6', 'row' => 2]
        ];

        foreach ($valuesPersonal as $value) {
            Value::create([
                'field_id' => $value['field_id'],
                'value' => $value['value'],
                'daily_sheet_id' => $dailySheetPersonal->id,
                'row' => $value['row'],
            ]);
        }

        foreach ($valuesMaquinaria as $value) {
            Value::create([
                'field_id' => $value['field_id'],
                'value' => $value['value'],
                'daily_sheet_id' => $dailySheetMaquinaria->id,
                'row' => $value['row'],
            ]);
        }
    }
}

