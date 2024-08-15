<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $administratorRole = Role::where('name','administrator')->first();
       $adminRole = Role::where('name','admin')->first();
       $managerRole = Role::where('name','manager')->first();

       $administrator = User::create([
        'name'=>'Administrator',
        'username'=>'administrator',
        'status'=>1,
        'email'=>'administrator@gmail.com',
        'email_verified_at'=>now(),
        'password'=>Hash::make(24635789),
        'remember_token'=>Str::random(10),
       ]);
       $admin = User::create([
        'name'=>'Admin',
        'username'=>'admin',
        'status'=>1,
        'email'=>'admin@gmail.com',
        'email_verified_at'=>now(),
        'password'=>Hash::make(24635789),
        'remember_token'=>Str::random(10),
       ]);
       $manager = User::create([
        'name'=>'Manager',
        'username'=>'manager',
        'status'=>1,
        'email'=>'manager@gmail.com',
        'email_verified_at'=>now(),
        'password'=>Hash::make(24635789),
        'remember_token'=>Str::random(10),
       ]);

       $administrator->roles()->attach($administratorRole);
       $admin->roles()->attach($adminRole);
       $manager->roles()->attach($managerRole);
    }
}
