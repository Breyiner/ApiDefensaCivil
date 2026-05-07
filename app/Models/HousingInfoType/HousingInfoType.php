<?php

namespace App\Models\HousingInfoType; 

use App\Models\HousingInfo\HousingInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingInfoType extends Model
{
    use HasFactory;

    protected $fillable = ['name',];

    public function housingInfo()
    {
        return $this->hasMany(\App\Models\HousingInfo\HousingInfo::class, 'housing_info_type_id');
    }
}
