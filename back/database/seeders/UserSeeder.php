<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
        ["name"=>"Admin","nombre"=>"Administrador","email"=>"admin@test.com",'password'=>Hash::make('admin123Admin'),"fechalimite"=>"2999-12-31",'unit_id'=>1,'rol'=>'ADMIN'],
        ["name"=>"caja1","nombre"=>"cajero","email"=>"cajero@test.com",'password'=>Hash::make('123456'),"fechalimite"=>"2999-12-31",'unit_id'=>2,'rol'=>'CAJERO'],
        ]);

    }
}
