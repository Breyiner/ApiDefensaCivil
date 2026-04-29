<?php

namespace Database\Seeders\HousingInfoType;

use App\Models\housingInfoType\HousingInfoType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class housingInfoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HousingInfoType::create([
            'name' => 'georeferencia'
        ]);
        HousingInfoType::create([
            'name' => 'grafico entorno'
        ]);
    }
}
