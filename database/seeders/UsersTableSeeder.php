<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
       

        User::create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('System admin');

        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Approve');
        
        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alicejohnson@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password2'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Bob Smith',
            'email' => 'bobsmith@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password3'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Carol Williams',
            'email' => 'carolwilliams@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password4'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'David Brown',
            'email' => 'davidbrown@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password5'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Emily Davis',
            'email' => 'emilydavis@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password6'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Frank Miller',
            'email' => 'frankmiller@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password7'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Grace Wilson',
            'email' => 'gracewilson@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password8'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Henry Moore',
            'email' => 'henrymoore@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password9'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
        User::create([
            'name' => 'Ivy Taylor',
            'email' => 'ivytaylor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password10'), // cambiar por una contraseña segura
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('Super Admin');
        
    }
}
