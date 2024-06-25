<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Company One',
                'rut_number' => 12345678,
                'rut_verifier' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Company Two',
                'rut_number' => 87654321,
                'rut_verifier' => 'K',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Puedes agregar más compañías aquí
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
    }
