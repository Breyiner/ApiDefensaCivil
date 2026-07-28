<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Importación de modelos relacionados para establecer relaciones Eloquent.
 */

use App\Models\BloodGroup\BloodGroup;
use App\Models\DocumentType\DocumentType;
use App\Models\Nationality\Nationality;
use App\Models\Gender\Gender;
use App\Models\Kinship\Kinship;
use App\Models\FamilyMember\FamilyMember;
use App\Models\ConditionMember\ConditionMember;
use App\Models\ActionPlan\ActionPlan;
use App\Models\Eps\Eps;
use App\Models\RiskReductionAction\RiskReductionAction;
use Carbon\Carbon;
/**
 * Clase Member
 * * Representa a un integrante del grupo familiar.
 * Centraliza los datos personales y las relaciones
 * necesarias para los procesos del sistema.
 */
class Member extends Model
{
    use HasFactory;

    /**
     * Atributos asignables de forma masiva.
     * @var array
     */
    protected $fillable = [
        'names',
        'last_names',
        'birth_date',
        'blood_group_id',
        'document_type_id',
        'document_number',
        'nationality_id',
        'gender_id',
        'kinship_id',
        'eps_id',
        'phone',
        'novelty',
    ];

    protected function names(): Attribute 
    {
        return Attribute::make(
            set: fn ($value) => $this -> normalizeName($value),
        );
    }

    protected function lastNames(): Attribute 
    {
        return Attribute::make(
            set: fn ($value) => $this -> normalizeName($value),
        );
    }

    private function normalizeName(?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        // Colapsa espacios múltiples y quita espacios al inicio/final
        $value = trim(preg_replace('/\s+/', ' ', $value));

        // minúsculas primero, luego mayúscula inicial en cada palabra
        return mb_convert_case(mb_strtolower($value, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * --- RELACIONES BELONGS TO (Muchos a Uno) ---
     */

    /**
     * Relación con el grupo sanguíneo del integrante.
     */
    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class);
    }

    /**
     * Relación con el tipo de documento del integrante.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Relación con la nacionalidad del integrante.
     */
    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    /**
     * Relación con el género del integrante.
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * Relación con el parentesco dentro del grupo familiar.
     */
    public function kinship()
    {
        return $this->belongsTo(Kinship::class);
    }

    // relacion con eps
    public function eps()
    {
        return $this->belongsTo(Eps::class);
    }

    // Relación con los planes familiares a los que pertenece el integrante.
    public function familyMember()
    {
        return $this->hasMany(FamilyMember::class, 'member_id');
    }

    // Relación con los condiciones de integrantes los que pertenece el integrante.
    public function conditionMember()
    {
        return $this->hasMany(ConditionMember::class, 'member_id');
    }

    public function actionPlan()
    {
        return $this->hasMany(ActionPlan::class, 'member_id');
    }

    /**
     * Relación con las acciones de reducción de riesgo del integrante.
     */
    public function riskReductionActions()
    {
        return $this->hasMany(RiskReductionAction::class, 'member_id');
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }
}
