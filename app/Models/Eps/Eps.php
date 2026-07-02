<?php

namespace App\Models\Eps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Member\Member;
use App\Models\Audit\Audit;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Eps extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','is_active'];

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->normalizeMayus($value),
        );
    }
    private function normalizeMayus(?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        // Colapsa espacios múltiples y quita espacios al inicio/final
        $value = trim(preg_replace('/\s+/', ' ', $value));

        // Todo a mayúsculas (soporta tildes/ñ)
        return mb_strtoupper($value, 'UTF-8');
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'eps_id');
    }

    /**
     * Relación polimórfica con auditoría
     * Permite registrar creación, edición, activación, desactivación o eliminación
     */
    public function audits()
    {
        return $this->morphMany(Audit::class, 'historiable');
    }

}
