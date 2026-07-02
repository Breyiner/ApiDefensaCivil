<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Importación de modelos para las relaciones de identidad y estructura.
 */
use App\Models\User\User;
use App\Models\DocumentType\DocumentType;
use App\Models\Gender\Gender;
use App\Models\Organization\Organization;

/**
 * Clase Profile
 * * Este modelo extiende la información del usuario del sistema. Implementa un 
 * modelo de "Perfil de Usuario" para mantener la tabla 'users' limpia y 
 * centralizar aquí los datos personales, demográficos y corporativos.
 */
class Profile extends Model
{
    use HasFactory;

    /**
     * Atributos asignables de forma masiva (Mass Assignment).
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',           // Relación 1:1 con la cuenta de usuario (Auth)
        'names',             // Nombres del usuario
        'last_names',        // Apellidos del usuario
        'birth_date',        // Fecha de nacimiento para cálculos de edad/ciclo vital
        'document_type_id',  // Referencia al tipo de documento (CC, TI, etc.)
        'document_number',   // Número de identificación único
        'phone',             // Teléfono de contacto
        'gender_id',         // Referencia al catálogo de géneros
        'organization_id'    // Organización a la que pertenece el funcionario
    ];

    /**
     * --- MUTATORS ---
     * Normalizan 'names' y 'last_names' antes de guardarlos: quitan espacios
     * extra y dejan cada palabra con su primera letra en mayúscula.
     * Ej: "cLAudia   HerNandez" -> "Claudia Hernandez"
     */

    protected function names(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->normalizeName($value),
        );
    }

    protected function lastNames(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->normalizeName($value),
        );
    }

    /**
     * Limpia espacios múltiples/extremos y aplica formato "Title Case"
     * respetando tildes y la letra ñ.
     */
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
     * --- RELACIONES BELONGS TO (Muchos a Uno / Uno a Uno) ---
     */

    /**
     * Relación con la cuenta de Usuario.
     * Permite acceder a las credenciales (email) desde el perfil.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el Tipo de Documento.
     * Facilita la obtención del nombre o acrónimo del documento.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * Relación con el Género.
     * Vincula el perfil con las opciones del catálogo demográfico.
     */
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    /**
     * Relación con la Organización.
     * Define la entidad bajo la cual el usuario opera en el sistema.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}