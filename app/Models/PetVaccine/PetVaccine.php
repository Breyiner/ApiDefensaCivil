<?php

namespace App\Models\PetVaccine;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pet\Pet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
    
class PetVaccine extends Model
{
    use HasFactory;

    protected $fillable = ['name','date','pet_id'];

    //la fecha de vacunación

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }
}
