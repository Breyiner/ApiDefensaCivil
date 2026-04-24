<?php

namespace App\Services\PDF;

use App\Models\User\User;
use App\Models\FamilyPlan\FamilyPlan;
use App\Models\VulnerableTest\VulnerableTest;
use App\Models\FamilyMember\FamilyMember;
use App\Models\Member\Member;
use App\Models\Pet\Pet;
use App\Models\PetVaccine\PetVaccine;
use App\Models\RiskFactor\RiskFactor;
use App\Models\Resource\Resource;
use App\Models\Action\Action;
use App\Models\ActionPlan\ActionPlan;
use App\Models\ActionPlanAction\ActionPlanAction;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{

    public static function byFamilyId($id)
    {
        $familyPlan = FamilyPlan::with([
            'familyMembers',
            'pets',
            'pets.species',
            'pets.animalGender',
            'pets.petVaccine',
            // 'riks',
            'riskFactors',
            'vulnerableTest',
            'vulnerableTest.vulnerableQuestion',
            'sector',
            // 'resources',
            // 'actionPlans',
            // 'actionPlans.actions'
            'city',
        ])->findOrFail($id);

        return PDF::loadView('pdf', [

            'familyPlan' => $familyPlan,
        ]);
    }
};
