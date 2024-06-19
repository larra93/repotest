<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_1 = Role::create(['name' => 'Super Admin']);
        $role_2 = Role::create(['name' => 'System admin']);
        $role_3 = Role::create(['name' => 'Approve']);
        $role_4 = Role::create(['name' => 'Contract admin']);

        Permission::create(['name' => 'contracts.create.index'])->syncRoles([$role_1, $role_2]);
        Permission::create(['name' => 'contracts.create'])->syncRoles([$role_1, $role_2]);
        Permission::create(['name' => 'contracts.edit'])->syncRoles([$role_1, $role_2]);
        Permission::create(['name' => 'contracts.destroy'])->syncRoles([$role_1, $role_2]);




        
    }
}
