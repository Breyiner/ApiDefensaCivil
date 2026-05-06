<?php

namespace Database\Seeders\familyType;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class familyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familyTypes = [
            ['name' => 'Vulnerable'],
            ['name' => 'No Vulnerable'],
            ['name' => 'Por definir'],
        ];

        foreach ($familyTypes as $type) {
            \App\Models\familyType\familyType::create($type);
        }
    }
}
