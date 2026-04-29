<?php

namespace App\Models\HousingInfo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Importación del modelo principal para la relación de integridad territorial y familiar.
 */
use App\Models\FamilyPlan\FamilyPlan;

/**
 * Clase HousingInfo
 * * Este modelo gestiona la información documental o multimedia de la vivienda.
 * Se utiliza principalmente para almacenar las rutas (paths) de archivos, fotos o
 * documentos PDF que respaldan la información recolectada en el Plan Familiar.
 */
class HousingInfo extends Model
{
    use HasFactory;

    /**
     * Atributos asignables de forma masiva (Mass Assignment).
     * * @var array
     */
    protected $fillable = [
        'id', 
        'family_plan_id', // ID del plan familiar al que pertenecen estos archivos
        'path',            // Ruta de almacenamiento del archivo (ej: 'uploads/housing/foto1.jpg')
        'housing_info_type_id'
    ];

    /**
     * --- RELACIÓN BELONGS TO (Muchos a Uno) ---
     * * Cada registro de información de vivienda pertenece obligatoriamente a un Plan Familiar.
     * Esta relación permite validar que no existan archivos "huérfanos" en el sistema.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function familyPlan()
    {
        /**
         * Retorna el objeto FamilyPlan vinculado a este registro de información.
         */
        return $this->belongsTo(FamilyPlan::class, 'family_plan_id');
    }
}