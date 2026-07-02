<?php

namespace Database\Seeders\Kinship;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kinship\Kinship;

class KinshipSeeder extends Seeder
{
    public function run(): void
    {
        Kinship::create(['name' => 'Cabeza de familia']);
        Kinship::create(['name' => 'Cónyuge']);
        Kinship::create(['name' => 'Hermano/a']);
        Kinship::create(['name' => 'Hijo/a']);
        Kinship::create(['name' => 'Padre/Madre']);
        Kinship::create(['name' => 'Tío/a']);
        Kinship::create(['name' => 'Primo/a']);
        Kinship::create(['name' => 'Padrastro']);
        Kinship::create(['name' => 'Madrastra']);
        Kinship::create(['name' => 'Hijastro']);
        Kinship::create(['name' => 'Sobrino/a']);
        Kinship::create(['name' => 'Nieto/a']);
        Kinship::create(['name' => 'Abuelo/a']);
        Kinship::create(['name' => 'Suegro/a']);
        Kinship::create(['name' => 'Yerno/Nuera']);
        Kinship::create(['name' => 'Cuñado/a']);
        Kinship::create(['name' => 'Conocido/a']);
    }
}
