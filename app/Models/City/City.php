<?php

namespace App\Models\City;

use App\Models\Audit\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Department\Department;
use App\Models\FamilyPlan\FamilyPlan;

/**
 * Modelo City
 * Representa las ciudades registradas en el sistema y su relación con la ubicación.
 */
class City extends Model
{
    use HasFactory;

    /**
     * Atributos asignables de forma masiva.
     * Incluye 'department_id' debido a la estructura de base de datos definida.
     */
    protected $fillable = [
        'id', 
        'name', 
        'department_id'
    ];

    /**
     * Relación de Uno a Muchos (One-to-Many).
     * Una ciudad puede estar vinculada a múltiples Planes Familiares.
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function familyPlan()
    {
        // Permite acceder a todos los planes familiares que pertenecen a esta ciudad
        return $this->hasMany(FamilyPlan::class, 'city_id');
    }

    /**
     * Relación Inversa (BelongsTo).
     * Cada ciudad pertenece a un registro de la tabla Departments.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        // Retorna el objeto Department relacionado con esta ciudad
        return $this->belongsTo(Department::class, 'department_id');
    }
    
    /**
     * --- RELACIÓN POLIMÓRFICA PARA HISTORIAL (AUDIT) ---
     * Permite registrar cambios, activaciones, desactivaciones,
     * eliminaciones o modificaciones de la seccional.
     */
    public function audits()
    {
        return $this->morphMany(Audit::class, 'historiable');
    }
}