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
        $role_1 = Role::create(['name' => 'super_admin']);
        $role_2 = Role::create(['name' => 'admin_sistema']);
        $role_3 = Role::create(['name' => 'revisor_pyc']);
        $role_4 = Role::create(['name' => 'revisor_cc']);
        $role_5 = Role::create(['name' => 'revisor_otra_area']);
        $role_6 = Role::create(['name' => 'admin_terreno']);
        $role_7 = Role::create(['name' => 'encargado_contratista']);
        $role_8 = Role::create(['name' => 'visualizador']);

        //Permisos revisar mas adelante para el filtrado de ventanas
        Permission::create(['name' => 'contracts.create.index'])->syncRoles([$role_1, $role_2]);
        Permission::create(['name' => 'contracts.create'])->syncRoles([$role_1, $role_2]);
        Permission::create(['name' => 'contracts.edit'])->syncRoles([$role_1, $role_2]);
        Permission::create(['name' => 'contracts.destroy'])->syncRoles([$role_1, $role_2]);




        
    }
}
