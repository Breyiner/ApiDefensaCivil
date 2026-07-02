<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\StateUser\StateUserSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\Gender\GenderSeeder;
use Database\Seeders\DocumentType\DocumentTypeSeeder;
use Database\Seeders\Sectional\SectionalSeeder;
use Database\Seeders\Organization\OrganizationSeeder;
use Database\Seeders\Profile\ProfileSeeder;
use Database\Seeders\Zone\ZoneSeeder;
use Database\Seeders\HousingQuality\HousingQualitySeeder;   
use Database\Seeders\Sector\SectorSeeder;
use Database\Seeders\StatusPlan\StatusPlanSeeder;
use Database\Seeders\Department\DepartmentSeeder;
use Database\Seeders\City\CitySeeder;
use Database\Seeders\Role\RoleSeeder;
use Database\Seeders\Permission\PermissionSeeder;
use Database\Seeders\RolePermission\RolePermissionSeeder;
use Database\Seeders\VulnerableQuestion\VulnerableQuestionSeeder;
use Database\Seeders\Action\ActionSeeder;
use Database\Seeders\AnimalGender\AnimalGenderSeeder;
use Database\Seeders\BloodGroup\BloodGroupSeeder;
use Database\Seeders\Nationality\NationalitySeeder;
use Database\Seeders\Kinship\KinshipSeeder;
use Database\Seeders\ConditionType\ConditionTypeSeeder;
use Database\Seeders\Eps\EpsSeeder;
use Database\Seeders\Species\SpeciesSeeder;
use Database\Seeders\ThreatType\ThreatTypeSeeder;
use Database\Seeders\Vulnerability\VulnerabilitySeeder;
use Database\Seeders\VulnerabilityGrade\VulnerabilityGradeSeeder;
use Database\Seeders\ActionType\ActionTypeSeeder;
use Database\Seeders\Resource\ResourceSeeder;
use Database\Seeders\HousingInfoType\housingInfoTypeSeeder;
use Database\Seeders\familyType\familyTypeSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            StateUserSeeder::class,
            UserSeeder::class,
            GenderSeeder::class,
            DocumentTypeSeeder::class,
            SectionalSeeder::class,
            OrganizationSeeder::class,
            ProfileSeeder::class,
            ZoneSeeder::class,
            HousingQualitySeeder::class,
            SectorSeeder::class,
            StatusPlanSeeder::class,
            DepartmentSeeder::class,
            CitySeeder::class,
            VulnerableQuestionSeeder::class,    
            ActionSeeder::class,
            BloodGroupSeeder::class,
            NationalitySeeder::class,
            KinshipSeeder::class,
            ConditionTypeSeeder::class,
            EpsSeeder::class,
            SpeciesSeeder::class,
            AnimalGenderSeeder::class,
            ThreatTypeSeeder::class,
            VulnerabilitySeeder::class,
            VulnerabilityGradeSeeder::class,
            ActionTypeSeeder::class,
            ResourceSeeder::class,
            housingInfoTypeSeeder::class,
            familyTypeSeeder::class,
        ]);
    }
}
