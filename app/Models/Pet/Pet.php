<?php

namespace App\Models\Pet;

use Illuminate\Database\Eloquent\Model;
use App\Models\FamilyPlan\FamilyPlan;
use App\Models\Species\Species;
use App\Models\AnimalGender\AnimalGender;
use App\Models\PetVaccine\PetVaccine;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Pet (Mascota)
 * 
 * Representa una mascota dentro del sistema, asociada a un plan familiar,
 * una especie y un género animal.
 */
class Pet extends Model
{
    use HasFactory;

    /**
     * Atributos asignables en masa
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'breed',
        'birth_date',
        'animal_gender_id',
        'species_id',
        'family_plan_id',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Atributos que deben añadirse a la serialización del modelo
     * Incluye el accessor 'age' para que esté disponible en JSON
     * 
     * @var array<int, string>
     */
    protected $appends = ['age'];

    /**
     * Relación: Una mascota pertenece a un plan familiar
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function familyPlan()
    {
        return $this->belongsTo(FamilyPlan::class, 'family_plan_id');
    }

    /**
     * Relación: Una mascota pertenece a una especie
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function species()
    {
        return $this->belongsTo(Species::class, 'species_id');
    }

    /**
     * Relación: Una mascota pertenece a un género animal
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function animalGender()
    {
        return $this->belongsTo(AnimalGender::class, 'animal_gender_id');
    }

    /**
     * Relación: Una mascota tiene muchas vacunas
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petVaccine()
    {
        return $this->hasMany(PetVaccine::class, 'pet_id');
    }

    /**
     * Accessor: Calcula la edad de la mascota en años
     * 
     * Este atributo virtual calcula automáticamente la edad
     * basándose en la fecha de nacimiento (birth_date)
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->birth_date)->age
        );
    }
}