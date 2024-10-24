<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('servicios')->insert([
            //['nombre'=>'VENTANILLA UNICA','abreviatura'=>'VU','color'=>'#1F7087'],
            ['nombre'=>'PISCINA','monto'=>5,'imagen'=>'piscina.jpg','unit_id'=>2],
            ['nombre'=>'PISCINA 3RA EDAD','monto'=>0,'imagen'=>'piscina.jpg','unit_id'=>2],
            ['nombre'=>'RESERVADO','monto'=>7,'imagen'=>'reservado.jpg','unit_id'=>2],
            ['nombre'=>'RESERVADO 3RA EDAD','monto'=>0,'imagen'=>'reservado.jpg','unit_id'=>2],
            ['nombre'=>'MINGITORIO','monto'=>1,'imagen'=>'banio.png','unit_id'=>2],
            ['nombre'=>'MINGITORIO 3RA EDAD','monto'=>0,'imagen'=>'banio.png','unit_id'=>2],
            ['nombre'=>'USO PARRILLERO','monto'=>15,'imagen'=>'parrilla.png','unit_id'=>2],
        ]);
    }
}
