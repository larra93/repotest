<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\States;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StatesSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
       
        States::create([
            'name' => 'A la espera contratista',
            'description' => 'Estado en el que el contratista aun no envia el Daily Report',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        States::create([
            'name' => 'Revisión Pendiente P&C',
            'description' => 'Estado en el que el revisor P&C aun no revisa el Daily Report',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        States::create([
            'name' => 'Revisión Pendiente CC',
            'description' => 'Estado en el que el revisor CC aun no revisa el Daily Report',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        States::create([
            'name' => 'Revisión Pendiente Otra área',
            'description' => 'Estado en el que el revisor de otra área aun no revisa el Daily Report',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        States::create([
            'name' => 'Aprobación Pendiente',
            'description' => 'Estado en el que el aprobador aun no revisa el Daily Report',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        States::create([
            'name' => 'Aprobado',
            'description' => 'Estado en el que el Daily Report fue aprobado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
     
    }
}
