<?php

namespace Database\Seeders\Profile;

use Illuminate\Database\Seeder;
use App\Models\Profile\Profile;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        Profile::create([
            'user_id' => 1,
            'names' => 'Iván Ramiro',
            'last_names' => 'Florez Hurtado',
            'birth_date' => '1995-01-01',
            'document_type_id' => 1,
            'document_number' => '12345678',
            'phone' => '1234567892',
            'gender_id' => 1,
            'organization_id' => 1,
        ]);

        Profile::create([
            'user_id' => 2,
            'names' => 'Lizbeth Katerine',
            'last_names' => 'Campillo Jimenez',
            'birth_date' => '1990-05-10',
            'document_type_id' => 1,
            'document_number' => '87654321',
            'phone' => '1234567891',
            'gender_id' => 2,
            'organization_id' => 1,
        ]);

        Profile::create([
            'user_id' => 3,
            'names' => 'Ronald Santiago',
            'last_names' => 'Gomez Herrera',
            'birth_date' => '1998-11-20',
            'document_type_id' => 1,
            'document_number' => '11223344',
            'phone' => '1234567893',
            'gender_id' => 1,
            'organization_id' => 1,
        ]);
    }
}