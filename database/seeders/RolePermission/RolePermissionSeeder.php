<?php

namespace Database\Seeders\RolePermission;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // $superAdmin = Role::findByName('Super Administrador');
        $admin      = Role::findByName('Administrador');
        $supervisor = Role::findByName('Supervisor');
        $voluntario = Role::findByName('Voluntario');

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMINISTRADOR → TODO
        |--------------------------------------------------------------------------
        */
        // $superAdmin->syncPermissions(Permission::all());

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRADOR → GESTIONA USUARIOS, PERMISOS Y ROLES / GESTIONA DATOS MAESTROS
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions(
            Permission::whereNotIn('name', [
                'family-plans.index',
                'family-plans.by-status',
                'family-plans.by-user',
                'family-plans.show',
                'family-plans.store',
                'family-plans.update',
                'family-plans.partial-update',
                'family-plans.identify',
                'family-plans.change-status',
                'family-plans.destroy',
                'family-plans.check-access',
                'family-plans.download-pdf',
                'family-plans.has-members',
                'family-plans.validate-requirements',
                'family-plans.change-family-type',
            ])->get()
        );

        // $admin->syncPermissions(Permission::all());

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR → REVISA, ACTUALIZA, CAMBIA ESTADOS
        |--------------------------------------------------------------------------
        */
        $supervisor->syncPermissions([
            'users.index', 'users.show',
            'profiles.index', 'profiles.show',
            'organizations.index', 'organizations.show',
            'cities.index', 'cities.show',
            'zones.index', 'zones.show',
            'sectors.index', 'sectors.show',
            'departments.index', 'departments.show',

            //Planes familiares
            'family-plans.index',
            'family-plans.show',
            'family-plans.update',
            'family-plans.partial-update',
            'family-plans.change-status',
            'family-plans.identify',
            'family-plans.check-access',
            'family-plans.has-members',
            'family-plans.validate-requirements',

            'housing-info.index',
            'housing-info.show',

            // Catálogos (gestión)
            'genders.index',
            'genders.update',
            'genders.change-status',
        
            'document-types.index',
            'document-types.update',
            'document-types.change-status',
        
            'housing-qualities.index',
            'housing-qualities.update',
            'housing-qualities.change-status',
        
            'status-plans.index',
            'status-plans.update',

            //GESTIÓN DE USUARIOS (solo voluntarios)
                'users.index',
                'users.by-status',
                'users.requests-admins',
                'users.requests-supervisors',
                'users.show',
                'users.history',
                'users.store',
                'users.update',
                'users.partial-update',
                'users.destroy',
                'users.change-status', 
                'users.approve-bulk',
                'users.change-status-bulk',
                'users.reject-delete-bulk',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VOLUNTARIO → REGISTRA Y CONSULTA
        |--------------------------------------------------------------------------
        */
        $voluntario->syncPermissions([
            // Catalogos (solo lectura)
            'home-frontend.voluntario',
            'genders.index',
            'document-types.index',
            'cities.index',
            'zones.index',
            'sectors.index',
            'housing-qualities.index',
            'status-plans.index',

            //Plan familiar
            'family-plans.index',
            'family-plans.show',
            'family-plans.store',

            //Vivienda
            'housing-info.store',

            //Perfil Propio
            'profiles.show',
            'profiles.update',
            'cities.by-department',
            'departments.index'
        ]);
    }
}
