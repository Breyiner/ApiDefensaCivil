<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

/**
 * Clase AppServiceProvider
 * * Centraliza la configuración global de la aplicación.
 * En este caso, se utiliza para registrar reglas de validación personalizadas (Custom Rules).
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar cualquier servicio de la aplicación.
     */
    public function register(): void
    {
        //
    }

    /**
     * Arrancar cualquier servicio de la aplicación.
     * Se ejecuta después de que todos los servicios han sido registrados.
     */
    public function boot(): void
    {
        /**
         * Regla: alpha_spaces
         * * Valida que el campo solo contenga letras (incluyendo acentos y ñ) y espacios.
         * Útil para: Nombres y Apellidos.
         */
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            // \pL permite cualquier letra en cualquier idioma (Unicode)
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        /**
         * Regla: alpha_num_spaces
         * * Valida letras, números y espacios.
         * Útil para: Direcciones o nombres de organizaciones.
         */
        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            return preg_match('/^[\pL\d\s]+$/u', $value);
        });

        /**
         * Regla: password_security
         * * Fuerza una política de contraseñas robusta.
         * Requiere: Al menos una minúscula, una mayúscula, un número y un carácter especial.
         */
        Validator::extend('password_security', function ($attribute, $value) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $value);
        });

        /**
         * Valida un valor de grupo sanguíneo básico.
         *
         * Reglas:
         * - Debe comenzar con una o dos letras (A, B, AB, O)
         * - Debe terminar obligatoriamente con un signo + o -
         * - Longitud máxima de 3 caracteres
         * - Letras mayusculas
         * Ejemplos válidos: A+, O-, AB+
         * Ejemplos inválidos: +A, A+B, ABC+, A, +
         */
        Validator::extend('blood_value', function ($attribute, $value) {
            return preg_match('/^[A-Z]{1,2}[+-]$/', $value);
        });

        /**
         * Regla: unique_document_by_type
         * * Valida la unicidad de un documento filtrado por su tipo.
         * Esto evita que se registre el mismo número de documento con el mismo tipo dos veces.
         */
        Validator::extend('unique_document_by_type', function ($attribute, $value, $parameters) {
            // Captura el tipo de documento del request actual
            $documentTypeId = request('document_type_id');

            if (!$documentTypeId) {
                return false; // Si no hay tipo de documento, la validación falla
            }

            // Verifica si ya existe la combinación número + tipo en la tabla 'profiles'
            return !DB::table('profiles')
                ->where('document_number', $value)
                ->where('document_type_id', $documentTypeId)
                ->exists();
        });
    }
}