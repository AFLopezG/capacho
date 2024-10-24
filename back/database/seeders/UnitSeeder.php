<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('units')->insert([
            ['nombre'=>'UNIDAD DE SISTEMAS'],
            ['nombre'=>'BALNEARIO DE CAPACHOS'],
            ['nombre'=>'PARQUE INTI RAYMI'],
            ['nombre'=>'BIO PARQUE ANDINO'],
        ]);

    }
}
