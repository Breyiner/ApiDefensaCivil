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
            'names' => 'Edixon David',
            'last_names' => 'Castillo Torres',
            'birth_date' => '1995-01-01',
            'document_type_id' => 1,
            'document_number' => '12345678',
            'phone' => '1234567892',
            'gender_id' => 1,
            'organization_id' => 1,
        ]);

        Profile::create([
            'user_id' => 2,
            'names' => 'Sol Any Valentina',
            'last_names' => 'Serrano Quintero',
            'birth_date' => '1990-05-10',
            'document_type_id' => 1,
            'document_number' => '87654321',
            'phone' => '1234567891',
            'gender_id' => 2,
            'organization_id' => 1,
        ]);

        Profile::create([
            'user_id' => 3,
            'names' => 'Dylan Santiago',
            'last_names' => 'Vesga Cañas',
            'birth_date' => '1998-11-20',
            'document_type_id' => 1,
            'document_number' => '11223344',
            'phone' => '1234567893',
            'gender_id' => 1,
            'organization_id' => 1,
        ]);

        Profile::create([
            'user_id' => 4,
            'names' => 'Breyner Alexis',
            'last_names' => 'Acosta Sandoval',
            'birth_date' => '1998-11-20',
            'document_type_id' => 1,
            'document_number' => '11223345',
            'phone' => '1234567894',
            'gender_id' => 1,
            'organization_id' => 1,
        ]);
        Profile::create([
            'user_id' => 5,
            'names' => 'Guillermo Elias',
            'last_names' => 'Garrido Maza',
            'birth_date' => '1998-11-20',
            'document_type_id' => 1,
            'document_number' => '11223346',
            'phone' => '1234567895',
            'gender_id' => 1,
            'organization_id' => 1,
        ]);
    }
}