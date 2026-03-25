<?php

namespace App\Models\Department;

use App\Models\Audit\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City\City;

/**
 * Modelo Apartment
 * Gestiona la información de los departamentos del pais (Departamentos).
 */
class Department extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = "departments";

    /**
     * Atributos asignables de forma masiva.
     * @var array
     */
    protected $fillable = [
        'id',   // Identificador único
        'name'  // Nombre del departamento
    ];

    /**
     * Relación de Uno a Muchos (One-to-Many).
     * Según la estructura definida, un departamento puede tener asociadas varias ciudades.
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function city()
    {
        // Define que este departamento es la "llave" para encontrar ciudades
        return $this->hasMany(City::class, 'department_id');
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
