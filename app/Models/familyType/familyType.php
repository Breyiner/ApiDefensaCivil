<?php

namespace App\Models\familyType;

use App\Models\FamilyPlan\FamilyPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class familyType extends Model
{
    use HasFactory;

    protected $fillable = ['name',];

    public function familyPlan()
    {
        return $this->hasOne(FamilyPlan::class, 'family_type_id');
    }
}
