<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('unidads')->insert([
            ['codigo' => 'kg', 'nombre' => 'Kilogramo'],
            ['codigo' => 'g',  'nombre' => 'Gramo'],
            ['codigo' => 'mg', 'nombre' => 'Miligramo'],
            ['codigo' => 'l',  'nombre' => 'Litro'],
            ['codigo' => 'ml', 'nombre' => 'Mililitro'],
            ['codigo' => 'ud', 'nombre' => 'Unidad'],
        ]);
    }
}
