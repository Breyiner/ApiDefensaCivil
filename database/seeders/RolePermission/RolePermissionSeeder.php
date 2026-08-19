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
                'audits.dashboard-supervisor',

                'users.requests-supervisors', // permiso solo para supervisores, sera ignorado por el rol admin
                
                'home-frontend.voluntario',
                'home-frontend.supervisor',
            ])->get()
        );

        // $admin->syncPermissions(Permission::all());

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR → REVISA, ACTUALIZA, CAMBIA ESTADOS
        |--------------------------------------------------------------------------
        */
        $supervisor->syncPermissions([

            Permission::whereNotIn('name', [

                'sectionals.history',
                'sectionals.store',
                'sectionals.update',
                'sectionals.partial-update',
                'sectionals.change-status',
                'sectionals.destroy',

                'organizations.history',
                'organizations.store',
                'organizations.update',
                'organizations.partial-update',
                'organizations.change-status',
                'organizations.destroy',

                'zones.store',
                'zones.update', 
                'zones.destroy',
                'sectors.history',
                'sectors.store',
                'sectors.update', 
                'sectors.partial-update',
                'sectors.change-status',
                'sectors.destroy',

                'departments.history',
                'departments.store',
                'departments.update',
                'departments.partial-update',
                'departments.destroy',

                'cities.history',
                'cities.store',
                'cities.update',
                'cities.partial-update',
                'cities.destroy',

                'housing-qualities.history',
                'housing-qualities.store',
                'housing-qualities.update', 
                'housing-qualities.partial-update',
                'housing-qualities.change-status',
                'housing-qualities.destroy',

                'blood-groups.store',
                'blood-groups.update',
                'blood-groups.destroy',
                
                'nationalities.history',
                'nationalities.store',
                'nationalities.update',
                'nationalities.partial-update',
                'nationalities.change-status',
                'nationalities.destroy',
                
                'kinships.store', 
                'kinships.update',
                'kinships.destroy',
                
                'species.history',
                'species.store',
                'species.update',
                'species.partial-update',
                'species.change-status',
                'species.destroy',
                
                'animal-genders.store',
                'animal-genders.update',
                'animal-genders.destroy',

                'threat-types.history',
                'threat-types.store',
                'threat-types.update',
                'threat-types.partial-update',
                'threat-types.change-status',
                'threat-types.destroy',

                'vulnerabilities.history',
                'vulnerabilities.store',
                'vulnerabilities.update',
                'vulnerabilities.partial-update',
                'vulnerabilities.change-status',
                'vulnerabilities.destroy',

                'resources.history',
                'resources.store', 
                'resources.update',
                'resources.partial-update',
                'resources.change-status',
                'resources.destroy',

                'audits.dashboard-admin',

                'users.requests-admins',

                'home-frontend.voluntario',
                'home-frontend.administrador',
            ])->get()
        ]);

        /*
        |--------------------------------------------------------------------------
        | VOLUNTARIO → REGISTRA Y CONSULTA
        |--------------------------------------------------------------------------
        */
        $voluntario->syncPermissions([

            Permission::whereNotIn('name', [

                'sectionals.history',
                'sectionals.store',
                'sectionals.update',
                'sectionals.partial-update',
                'sectionals.change-status',
                'sectionals.destroy',

                'organizations.history',
                'organizations.store',
                'organizations.update',
                'organizations.partial-update',
                'organizations.change-status',
                'organizations.destroy',

                'zones.store',
                'zones.update', 
                'zones.destroy',
                'sectors.history',
                'sectors.store',
                'sectors.update', 
                'sectors.partial-update',
                'sectors.change-status',
                'sectors.destroy',

                'departments.history',
                'departments.store',
                'departments.update',
                'departments.partial-update',
                'departments.destroy',
                
                'cities.history',
                'cities.store',
                'cities.update',
                'cities.partial-update',
                'cities.destroy',

                'housing-qualities.history',
                'housing-qualities.store',
                'housing-qualities.update', 
                'housing-qualities.partial-update',
                'housing-qualities.change-status',
                'housing-qualities.destroy',

                'blood-groups.store',
                'blood-groups.update',
                'blood-groups.destroy',
                
                'nationalities.history',
                'nationalities.store',
                'nationalities.update',
                'nationalities.partial-update',
                'nationalities.change-status',
                'nationalities.destroy',
                
                'kinships.store', 
                'kinships.update',
                'kinships.destroy',
                
                'species.history',
                'species.store',
                'species.update',
                'species.partial-update',
                'species.change-status',
                'species.destroy',

                'animal-genders.store',
                'animal-genders.update',
                'animal-genders.destroy',

                'threat-types.history',
                'threat-types.store',
                'threat-types.update',
                'threat-types.partial-update',
                'threat-types.change-status',
                'threat-types.destroy',

                'vulnerabilities.history',
                'vulnerabilities.store',
                'vulnerabilities.update',
                'vulnerabilities.partial-update',
                'vulnerabilities.change-status',
                'vulnerabilities.destroy',

                'resources.history',
                'resources.store', 
                'resources.update',
                'resources.partial-update',
                'resources.change-status',
                'resources.destroy',

                'audits.dashboard-admin',
                'audits.dashboard-supervisor',

                'audits.delete_id',
                'audits.delete_bulk',

                'users.requests-supervisors',
                'users.requests-admins',
                
                'profiles.history',

                'home-frontend.supervisor',
                'home-frontend.administrador',
            ])->get()
        ]);
    }
}
