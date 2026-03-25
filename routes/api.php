<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\API\Account\AccountController;
use App\Http\Controllers\API\Account\AccountPasswordController;
use App\Http\Controllers\API\Account\AccountVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StateUser\StateUserController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\API\Gender\GenderController;
use App\Http\Controllers\API\DocumentType\DocumentTypeController;
use App\Http\Controllers\API\Sectional\SectionalController;
use App\Http\Controllers\API\Organization\OrganizationController;
use App\Http\Controllers\API\Profile\ProfileController;
use App\Http\Controllers\API\Auth\AuthenticationController;
use App\Http\Controllers\API\Zone\ZoneController;
use App\Http\Controllers\API\HousingQuality\HousingQualityController;
use App\Http\Controllers\API\Sector\SectorController;
use App\Http\Controllers\API\StatusPlan\StatusPlanController;
use App\Http\Controllers\API\Department\DepartmentController;
use App\Http\Controllers\API\City\CityController;
use App\Http\Controllers\API\FamilyPlan\FamilyPlanController;
use App\Http\Controllers\API\HousingInfo\HousingInfoController;
use App\Http\Controllers\API\VulnerableQuestion\VulnerableQuestionController;
use App\Http\Controllers\API\VulnerableTest\VulnerableTestController;
use App\Http\Controllers\API\Action\ActionController;
use App\Http\Controllers\API\BloodGroup\BloodGroupController;
use App\Http\Controllers\API\Nationality\NationalityController;
use App\Http\Controllers\API\Kinship\KinshipController;
use App\Http\Controllers\API\Member\MemberController;
use App\Http\Controllers\API\FamilyMember\FamilyMemberController;
use App\Http\Controllers\API\ConditionType\ConditionTypeController;
use App\Http\Controllers\API\ConditionMember\ConditionMemberController;
use App\Http\Controllers\API\Species\SpeciesController;
use App\Http\Controllers\API\AnimalGender\AnimalGenderController;
use App\Http\Controllers\API\Pet\PetController;
use App\Http\Controllers\API\PetVaccine\PetVaccineController;
use App\Http\Controllers\API\RiskFactor\RiskFactorController;
use App\Http\Controllers\API\RiskReductionAction\RiskReductionActionController;
use App\Http\Controllers\API\ThreatType\ThreatTypeController;
use App\Http\Controllers\API\VulnerabilityFactor\VulnerabilityFactorController;
use App\Http\Controllers\API\VulnerabilityGrade\VulnerabilityGradeController;
use App\Http\Controllers\API\Vulnerability\VulnerabilityController;
use App\Http\Controllers\API\Resource\ResourceController;
use App\Http\Controllers\API\Audit\AuditController;
use App\Http\Controllers\API\HousingGraphic\HousingGraphicController;
use App\Http\Controllers\API\ActionPlan\ActionPlanController;
use App\Http\Controllers\API\ActionPlanAction\ActionPlanActionController;
use App\Http\Controllers\API\ActionType\ActionTypeController;
use App\Http\Controllers\API\AvailableResource\AvailableResourceController;
use App\Http\Controllers\API\Notification\NotificationController;
use App\Http\Controllers\API\EmailVerification\EmailVerificationController;
use App\Http\Controllers\API\PasswordReset\PasswordResetController;

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::get('/documentTypesPublic', [DocumentTypeController::class, 'index']);
Route::get('/gendersPublic', [GenderController::class, 'index']);
Route::get('/sectionalsPublic', [SectionalController::class, 'getActiveWithOrganization']);
Route::get('/organizationsPublic/sectional/{sectional_id}', [OrganizationController::class, 'getSectional']);

/**
 * GRUPO: Verificación de email.
 *
 * Rutas relacionadas con el flujo de verificación de email.
 * No requieren autenticación completa.
 */

// Redirige al usuario si no ha verificado (muestra aviso)
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->name('verification.notice');

// Procesa el enlace de verificación del email (URL firmada)
// middleware 'signed': verifica que la URL no fue manipulada
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

// Reenvía el email de verificación
// throttle:verification = 6 req/min para prevenir spam
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    // ->middleware('throttle:verification')
    ->name('verification.send');


Route::post('/password/forgot',  [PasswordResetController::class, 'forgot']);   // envía código
Route::post('/password/resend', [PasswordResetController::class, 'resend']);    // reenvia código
Route::post('/password/verify',  [PasswordResetController::class, 'verify']);   // valida código
Route::post('/password/reset',   [PasswordResetController::class, 'reset']);    // nueva contraseña


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/refresh-token', [AuthenticationController::class, 'refreshToken'])
        ->middleware('ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value);


    Route::post('/logout', [AuthenticationController::class, 'logOut']);


    Route::prefix('account')->group(function () {

        // -------------------------------------------------------------------------
        // Verificación de identidad
        // Genera un token temporal tras validar la contraseña actual
        // -------------------------------------------------------------------------
        Route::post('/verify-password', [AccountVerificationController::class, 'verify']);


        // -------------------------------------------------------------------------
        // Datos sensibles de la cuenta
        // Requieren token de verificación de contraseña (un solo uso, TTL 10 min)
        // -------------------------------------------------------------------------
        Route::middleware('password.verify:change_email')
            ->patch('/email', [AccountController::class, 'updateEmail']);

        // -------------------------------------------------------------------------
        // Cambio de contraseña
        // Verifica contraseña actual en el body, no requiere token previo
        // -------------------------------------------------------------------------
        Route::patch('/password', [AccountPasswordController::class, 'updatePassword']);
    });


    Route::prefix('statusPlans')->group(function () {
        Route::get('/', [StatusPlanController::class, 'index']);

        Route::get('/{statusPlan_id}', [StatusPlanController::class, 'show']);

        Route::post('/', [StatusPlanController::class, 'store']);

        Route::put('/{statusPlan_id}', [StatusPlanController::class, 'update']);

        Route::delete('/{statusPlan_id}', [StatusPlanController::class, 'destroy']);
    });
    route::prefix('stateUsers')->group(function () {
        route::get('/', [StateUserController::class, 'index']);

        route::get('/{state_user_id}', [StateUserController::class, 'show']);

        route::post('/', [StateUserController::class, 'store']);

        route::put('/{state_user_id}', [StateUserController::class, 'update']);

        route::delete('/{state_user_id}', [StateUserController::class, 'destroy']);
    });

    route::prefix('users')->group(function () {
        route::get('/', [UserController::class, 'index']);

        // Ruta para filtrar por estado usando query param (?status=1)
        Route::get('/by-status', [UserController::class, 'getByStatus']);

        route::get('/requestsAdmins', [UserController::class, 'getRequestsAdmins']);

        // route::get('/userForAdmin', [UserController::class, 'getUserForAdmins']);

        route::get('/requestsSupervisors', [UserController::class, 'getRequestsSupervisors']);

        // route::get('/userForSupervisor', [UserController::class, 'getUserForSupervisors']);

        route::get('/{user_id}', [UserController::class, 'show']);

        route::get('/history/{user_id}', [UserController::class, 'history']);

        route::post('/', [UserController::class, 'store']);

        route::put('/{user_id}', [UserController::class, 'update']);

        route::patch('/{user_id}', [UserController::class, 'partialUpdate']);

        route::patch('/role/{user_id}', [UserController::class, 'changeRole']);

        route::patch('/status/{user_id}', [UserController::class, 'changeStatus']);

        route::delete('/{user_id}', [UserController::class, 'destroy']);
    });

    route::prefix('genders')->group(function () {
        route::get('/', [GenderController::class, 'index']);

        route::get('/{gender_id}', [GenderController::class, 'show']);

        route::get('/history/{gender_id}', [GenderController::class, 'history']);

        route::post('/', [GenderController::class, 'store']);

        route::put('/{gender_id}', [GenderController::class, 'update']);

        route::patch('/{gender_id}', [GenderController::class, 'partialUpdate']);

        route::patch('/status/{gender_id}', [GenderController::class, 'changeStatus']);

        route::delete('/{gender_id}', [GenderController::class, 'destroy']);
    });

    route::prefix('documentTypes')->group(function () {
        route::get('/', [DocumentTypeController::class, 'index']);
        route::get('/{documentType_id}', [DocumentTypeController::class, 'show']);
        route::get('/history/{documentType_id}', [DocumentTypeController::class, 'history']);
        route::post('/', [DocumentTypeController::class, 'store']);
        route::put('/{documentType_id}', [DocumentTypeController::class, 'update']);
        route::patch('/{documentType_id}', [DocumentTypeController::class, 'partialUpdate']);
        route::patch('/status/{documentType_id}', [DocumentTypeController::class, 'changeStatus']);
        route::delete('/{documentType_id}', [DocumentTypeController::class, 'destroy']);
    });
    route::prefix('sectionals')->group(function () {
        route::get('/', [SectionalController::class, 'index']);

        route::get('/active-with-organizations', [SectionalController::class, 'getActiveWithOrganization']);

        route::get('/{sectional_id}', [SectionalController::class, 'show']);

        route::get('/history/{sectional_id}', [SectionalController::class, 'history']);

        route::post('/', [SectionalController::class, 'store']);

        route::put('/{sectional_id}', [SectionalController::class, 'update']);

        route::patch('/{sectional_id}', [SectionalController::class, 'partialUpdate']);

        route::patch('/status/{sectional_id}', [SectionalController::class, 'changeStatus']);

        route::delete('/{sectional_id}', [SectionalController::class, 'destroy']);
    });

    route::prefix('organizations')->group(function () {
        route::get('/', [OrganizationController::class, 'index']);

        route::get('/{organization_id}', [OrganizationController::class, 'show']);

        route::get('/sectional/{sectional_id}', [OrganizationController::class, 'getSectional']);

        route::get('/history/{sectional_id}', [OrganizationController::class, 'history']);

        route::post('/', [OrganizationController::class, 'store']);

        route::put('/{organization_id}', [OrganizationController::class, 'update']);

        route::patch('/{organization_id}', [OrganizationController::class, 'partialUpdate']);

        route::patch('/status/{organization_id}', [OrganizationController::class, 'changeStatus']);

        route::delete('/{organization_id}', [OrganizationController::class, 'destroy']);
    });

    route::prefix('profiles')->group(function () {
        route::get('/', [ProfileController::class, 'index']);

        route::get('/{profile_id}', [ProfileController::class, 'show']);

        route::post('/', [ProfileController::class, 'store']);

        route::put('/{profile_id}', [ProfileController::class, 'update']);

        route::patch('/{profile_id}', [ProfileController::class, 'partialUpdate']);

        route::delete('/{profile_id}', [ProfileController::class, 'destroy']);
    });

    route::prefix('zones')->group(function () {
        route::get('/', [ZoneController::class, 'index'])->middleware('permission:zones.index');

        route::get('/{zone_id}', [ZoneController::class, 'show']);

        route::post('/', [ZoneController::class, 'store']);

        route::put('/{zone_id}', [ZoneController::class, 'update']);

        route::delete('/{zone_id}', [ZoneController::class, 'destroy']);
    });

    route::prefix('housingQualities')->group(function () {
        route::get('/', [HousingQualityController::class, 'index'])->middleware('permission:housing-qualities.index');

        route::get('/{housingQuality_id}', [HousingQualityController::class, 'show']);

        route::get('/history/{housingQuality_id}', [HousingQualityController::class, 'history']);

        route::post('/', [HousingQualityController::class, 'store']);

        route::put('/{housingQuality_id}', [HousingQualityController::class, 'update']);

        route::patch('/{housingQuality_id}', [HousingQualityController::class, 'partialUpdate']);

        route::patch('/status/{housingQuality_id}', [HousingQualityController::class, 'changeStatus']);

        route::delete('/{housingQuality_id}', [HousingQualityController::class, 'destroy']);
    });

    route::prefix('sectors')->group(function () {
        route::get('/', [SectorController::class, 'index'])->middleware('permission:sectors.index');

        route::get('/{sector_id}', [SectorController::class, 'show']);

        route::get('/history/{sector_id}', [SectorController::class, 'history']);

        route::post('/', [SectorController::class, 'store']);

        route::put('/{sector_id}', [SectorController::class, 'update']);

        route::patch('/{sector_id}', [SectorController::class, 'partialUpdate']);

        route::patch('/status/{sector_id}', [SectorController::class, 'changeStatus']);

        route::delete('/{sector_id}', [SectorController::class, 'destroy']);
    });

    route::prefix('departments')->group(function () {
        route::get('/', [DepartmentController::class, 'index'])->middleware('permission:departments.index');

        route::get('/{department_id}', [DepartmentController::class, 'show']);

        route::get('/history/{department_id}', [DepartmentController::class, 'history']);

        route::post('/', [DepartmentController::class, 'store']);

        route::put('/{department_id}', [DepartmentController::class, 'update']);

        route::delete('/{department_id}', [DepartmentController::class, 'destroy']);

        route::patch('/{department_id}', [DepartmentController::class, 'partialUpdate']);
    });

    route::prefix('cities')->group(function () {
        route::get('/', [CityController::class, 'index']);

        route::get('/{city_id}', [CityController::class, 'show']);

        route::get('/department/{department_id}', [CityController::class, 'getByDepartment'])->middleware('permission:cities.departments');

        route::post('/', [CityController::class, 'store']);

        route::put('/{city_id}', [CityController::class, 'update']);

        route::patch('/{city_id}', [CityController::class, 'partialUpdate']);

        route::delete('/{city_id}', [CityController::class, 'destroy']);
    });

    route::prefix('familyPlans')->group(function () {
        route::get('/', [FamilyPlanController::class, 'index']);

        // Ruta para filtrar por estado usando query param (?status=1)
        Route::get('/by-status', [FamilyPlanController::class, 'getByStatus']);

        route::get('/byUser', [FamilyPlanController::class, 'getFamilyPlanByUser']);

        route::get('/{familyPlan_id}', [FamilyPlanController::class, 'show'])->middleware('permission:family-plans.show');

        route::post('/', [FamilyPlanController::class, 'store'])->middleware('permission:family-plans.store');

        route::put('/{familyPlan_id}', [FamilyPlanController::class, 'update']);

        route::patch('/{familyPlan_id}', [FamilyPlanController::class, 'partialUpdate']);

        route::patch('/identify/{familyPlan_id}', [FamilyPlanController::class, 'identify'])->middleware('permission:family-plans.identify');

        route::patch('/status/{familyPlan_id}', [FamilyPlanController::class, 'changeStatus']);

        route::delete('/{familyPlan_id}', [FamilyPlanController::class, 'destroy']);

        route::get('/checkAccess/{familyPlan_id}', [FamilyPlanController::class, 'checkAccess']);

        Route::get('/pdf/{id}', [FamilyPlanController::class, 'downloadPdf']);

        Route::get('/{familyPlan_id}/has-members', [FamilyPlanController::class, 'hasMembers']);

        Route::get('/{familyPlan_id}/validate-requirements', [FamilyPlanController::class, 'validateRequirements']);
    });

    route::prefix('housingInfo')->group(function () {
        route::get('/', [HousingInfoController::class, 'index']);

        route::get('/{housingInfo_id}', [HousingInfoController::class, 'show'])->middleware('permission:housing-info.show');

        route::post('/', [HousingInfoController::class, 'store'])->middleware('permission:housing-info.store');

        route::delete('/{housingInfo_id}', [HousingInfoController::class, 'destroy'])->middleware('permission:housing-info.destroy');
    });


    route::prefix('vulnerableQuestions')->group(function () {
        route::get('/', [VulnerableQuestionController::class, 'index']);

        route::get('/paginate', [VulnerableQuestionController::class, 'paginate']);

        route::get('/{vulnerableQuestion_id}', [VulnerableQuestionController::class, 'show']);

        route::get('history/{vulnerableQuestion_id}', [VulnerableQuestionController::class, 'history']);

        route::post('/', [VulnerableQuestionController::class, 'store']);

        route::put('/{vulnerableQuestion_id}', [VulnerableQuestionController::class, 'update']);

        route::patch('/{vulnerableQuestion_id}', [VulnerableQuestionController::class, 'partialUpdate']);

        route::patch('/status/{vulnerableQuestion_id}', [VulnerableQuestionController::class, 'changeStatus']);

        route::delete('/{vulnerableQuestion_id}', [VulnerableQuestionController::class, 'destroy']);
    });

    route::prefix('vulnerableTest')->group(function () {
        route::get('/', [VulnerableTestController::class, 'index']);

        route::get('/{familyPlan_id}', [VulnerableTestController::class, 'show']);

        route::post('/', [VulnerableTestController::class, 'store']);

        route::delete('/{familyPlan_id}', [VulnerableTestController::class, 'destroy']);
    });

    route::prefix('actions')->group(function () {
        route::get('/', [ActionController::class, 'index']);

        route::get('/{action_id}', [ActionController::class, 'show']);

        route::post('/', [ActionController::class, 'store']);

        route::put('/{action_id}', [ActionController::class, 'update']);

        route::delete('/{action_id}', [ActionController::class, 'destroy']);
    });

    // Rutas para el catálogo de grupos sanguíneos
    Route::prefix('bloodGroups')->group(function () {

        // Obtener todos los grupos sanguíneos
        Route::get('/', [BloodGroupController::class, 'index']);

        // Obtener un grupo sanguíneo por su ID
        Route::get('/{bloodGroup_id}', [BloodGroupController::class, 'show']);

        // Crear un nuevo grupo sanguíneo
        Route::post('/', [BloodGroupController::class, 'store']);

        // Actualizar completamente un grupo sanguíneo
        Route::put('/{bloodGroup_id}', [BloodGroupController::class, 'update']);

        // Eliminar un grupo sanguíneo
        Route::delete('/{bloodGroup_id}', [BloodGroupController::class, 'destroy']);
    });

    // Rutas para el catálogo de nacionalidades
    Route::prefix('nationalities')->group(function () {

        // Obtener todas las nacionalidades
        Route::get('/', [NationalityController::class, 'index']);

        // Obtener una nacionalidad por su ID
        Route::get('/{nationality_id}', [NationalityController::class, 'show']);

        Route::get('/history/{nationality_id}', [NationalityController::class, 'history']);

        // Crear una nueva nacionalidad
        Route::post('/', [NationalityController::class, 'store']);

        // Actualizar completamente una nacionalidad
        Route::put('/{nationality_id}', [NationalityController::class, 'update']);

        // Actualizar parcialmente una nacionalidad
        Route::patch('/{nationality_id}', [NationalityController::class, 'partialUpdate']);

        // Cambiar el estado (activa / inactiva) de una nacionalidad
        Route::patch('/status/{nationality_id}', [NationalityController::class, 'changeStatus']);

        // Eliminar una nacionalidad
        Route::delete('/{nationality_id}', [NationalityController::class, 'destroy']);
    });
    // Rutas para el catálogo de grupos sanguíneos
    Route::prefix('kinships')->group(function () {

        // Obtener todos los grupos sanguíneos
        Route::get('/', [KinshipController::class, 'index']);

        // Obtener un grupo sanguíneo por su ID
        Route::get('/{kinship_id}', [KinshipController::class, 'show']);

        // Crear un nuevo grupo sanguíneo
        Route::post('/', [KinshipController::class, 'store']);

        // Actualizar completamente un grupo sanguíneo
        Route::put('/{kinship_id}', [KinshipController::class, 'update']);

        // Eliminar un grupo sanguíneo
        Route::delete('/{kinship_id}', [KinshipController::class, 'destroy']);
    });

    Route::prefix('members')->group(function () {
        // Obtener todos los miembros
        Route::get('/', [MemberController::class, 'index']);
        // Obtener un miembro por su ID
        Route::get('/{member_id}', [MemberController::class, 'show']);
        // Obtener miembros asociados a un plan familiar
        Route::get('/familyPlan/{plan_id}', [MemberController::class, 'getMembersForPlan']);
        // Obtener miembros asociados a un plan familiar para el select de actionPlan
        Route::get('/familyPlan/select/{plan_id}', [MemberController::class, 'getMembersSelect']);
        // Registrar un nuevo miembro
        Route::post('/{plan_id}', [MemberController::class, 'store']);
        // Actualizar completamente un miembro
        Route::put('/{member_id}', [MemberController::class, 'update']);
        // Actualizar parcialmente un miembro
        Route::patch('/{member_id}', [MemberController::class, 'partialUpdate']);
        // Eliminar un miembro
        Route::delete('/{member_id}', [MemberController::class, 'destroy']);
    });

    Route::prefix('familyMembers')->group(function () {
        // Obtener todos los miembros
        Route::get('/', [FamilyMemberController::class, 'index']);
        // Obtener un miembro por su ID
        Route::get('/{familyMember_id}', [FamilyMemberController::class, 'show']);
        // Registrar un nuevo miembro
        Route::post('/', [FamilyMemberController::class, 'store']);
        // Actualizar completamente un miembro
        Route::put('/{familyMember_id}', [FamilyMemberController::class, 'update']);
        // Eliminar un miembro
        Route::delete('/{familyMember_id}', [FamilyMemberController::class, 'destroy']);
    });

    Route::prefix('conditionTypes')->group(function () {
        // Obtener todos los tipos de condición
        Route::get('/', [ConditionTypeController::class, 'index']);
        // Obtener un tipo de condición por su ID
        Route::get('/{conditionType_id}', [ConditionTypeController::class, 'show']);
        // Registrar un nuevo tipo de condición
        Route::post('/', [ConditionTypeController::class, 'store']);
        // Actualizar completamente un tipo de condición
        Route::put('/{conditionType_id}', [ConditionTypeController::class, 'update']);
        // Eliminar un tipo de condición
        Route::delete('/{conditionType_id}', [ConditionTypeController::class, 'destroy']);
    });

    Route::prefix('conditionMembers')->group(function () {
        // Obtener todos los registros
        Route::get('/', [ConditionMemberController::class, 'index']);
        // Obtener un registro específico por su ID
        Route::get('/{conditionMember_id}', [ConditionMemberController::class, 'show']);
        // Obtener todos los registros de un miembro específico
        Route::get('/member/{member_id}', [ConditionMemberController::class, 'getByMember']);
        // Crear un nuevo registro (POST) -> plan_id en la ruta aunque no se usa
        Route::post('/', [ConditionMemberController::class, 'store']);
        // Actualización total (PUT) -> plan_id en la ruta aunque no se usa
        Route::put('/{conditionMember_id}', [ConditionMemberController::class, 'update']);
        // Actualización parcial (PATCH) -> plan_id en la ruta aunque no se usa
        Route::patch('/{conditionMember_id}', [ConditionMemberController::class, 'partialUpdate']);
        // Eliminar un registro (DELETE) -> plan_id en la ruta aunque no se usa
        Route::delete('/{conditionMember_id}', [ConditionMemberController::class, 'destroy']);
    });

    Route::prefix('species')->group(function () {
        // Obtener todas las especies
        Route::get('/', [SpeciesController::class, 'index']);
        // Obtener una especie por ID
        Route::get('/{specie_id}', [SpeciesController::class, 'show']);

        Route::get('/history/{specie_id}', [SpeciesController::class, 'history']);
        // Crear nueva especie
        Route::post('/', [SpeciesController::class, 'store']);
        // Actualización completa
        Route::put('/{specie_id}', [SpeciesController::class, 'update']);
        // Actualización parcial
        Route::patch('/{specie_id}', [SpeciesController::class, 'partialUpdate']);

        Route::patch('/status/{specie_id}', [SpeciesController::class, 'changeStatus']);
        // Eliminar especie
        Route::delete('/{specie_id}', [SpeciesController::class, 'destroy']);
    });

    Route::prefix('animalGenders')->group(function () {
        // Obtener todos los géneros de animales
        Route::get('/', [AnimalGenderController::class, 'index']);
        // Obtener un género de animal por ID
        Route::get('/{id}', [AnimalGenderController::class, 'show']);
        // Crear nuevo género de animal
        Route::post('/', [AnimalGenderController::class, 'store']);
        // Actualización completa
        Route::put('/{id}', [AnimalGenderController::class, 'update']);
        // Eliminar género de animal
        Route::delete('/{id}', [AnimalGenderController::class, 'destroy']);
    });

    Route::prefix('pets')->group(function () {
        Route::get('/', [PetController::class, 'index']);

        Route::get('/{id}', [PetController::class, 'show']);

        Route::get('/familyPlan/{plan_id}', [PetController::class, 'getPetsForPlan']);

        Route::post('/', [PetController::class, 'store']);

        Route::put('/{id}', [PetController::class, 'update']);

        Route::patch('/{id}', [PetController::class, 'partialUpdate']);

        Route::delete('/{id}', [PetController::class, 'destroy']);
    });

    Route::prefix('petVaccines')->group(function () {
        Route::get('/', [PetVaccineController::class, 'index']);

        Route::get('/{id}', [PetVaccineController::class, 'show']);

        Route::get('/pet/{pet_id}', [PetVaccineController::class, 'getVaccinesForPets']);

        Route::post('/', [PetVaccineController::class, 'store']);

        Route::put('/{id}', [PetVaccineController::class, 'update']);

        Route::patch('/{id}', [PetVaccineController::class, 'partialUpdate']);

        Route::delete('/{id}', [PetVaccineController::class, 'destroy']);
    });

    Route::prefix('threatTypes')->group(function () {

        Route::get('/', [ThreatTypeController::class, 'index']);

        Route::get('/{threatType_id}', [ThreatTypeController::class, 'show']);

        Route::get('/history/{threatType_id}', [ThreatTypeController::class, 'history']);

        Route::post('/', [ThreatTypeController::class, 'store']);

        Route::put('/{threatType_id}', [ThreatTypeController::class, 'update']);

        Route::patch('/{threatType_id}', [ThreatTypeController::class, 'partialUpdate']);

        Route::patch('/status/{threatType_id}', [ThreatTypeController::class, 'changeStatus']);

        Route::delete('/{threatType_id}', [ThreatTypeController::class, 'destroy']);
    });

    Route::prefix('riskFactors')->group(function () {
        Route::get('/', [RiskFactorController::class, 'index']);

        Route::get('/{id}', [RiskFactorController::class, 'show']);

        Route::get('/familyPlan/{family_plan_id}', [RiskFactorController::class, 'getForPlan']);

        Route::get('/familyPlan/select/{family_plan_id}', [RiskFactorController::class, 'getRiskFactorSelect']);

        Route::post('/', [RiskFactorController::class, 'store']);

        Route::put('/{id}', [RiskFactorController::class, 'update']);

        Route::patch('/{id}', [RiskFactorController::class, 'partialUpdate']);

        Route::delete('/{id}', [RiskFactorController::class, 'destroy']);
    });

    Route::prefix('riskReductionActions')->group(function () {

        Route::get('/', [RiskReductionActionController::class, 'index']);

        Route::get('/{id}', [RiskReductionActionController::class, 'show']);

        Route::get('/riskFactor/{riskFactor_id}', [RiskReductionActionController::class, 'getByRiskFactor']);

        Route::post('/', [RiskReductionActionController::class, 'store']);

        Route::put('/{id}', [RiskReductionActionController::class, 'update']);

        Route::patch('/{id}', [RiskReductionActionController::class, 'partialUpdate']);

        Route::delete('/{id}', [RiskReductionActionController::class, 'destroy']);
    });

    Route::prefix('vulnerabilityGrades')->group(function () {

        Route::get('/', [VulnerabilityGradeController::class, 'index']);

        Route::get('/{id}', [VulnerabilityGradeController::class, 'show']);

        Route::post('/', [VulnerabilityGradeController::class, 'store']);

        Route::put('/{id}', [VulnerabilityGradeController::class, 'update']);

        Route::delete('/{id}', [VulnerabilityGradeController::class, 'destroy']);
    });

    Route::prefix('vulnerabilities')->group(function () {

        Route::get('/', [VulnerabilityController::class, 'index']);

        Route::get('/{vulnerability_id}', [VulnerabilityController::class, 'show']);

        Route::get('/history/{vulnerability_id}', [VulnerabilityController::class, 'history']);

        Route::post('/', [VulnerabilityController::class, 'store']);

        Route::put('/{vulnerability_id}', [VulnerabilityController::class, 'update']);

        Route::patch('/{vulnerability_id}', [VulnerabilityController::class, 'partialUpdate']);

        Route::patch('/status/{vulnerability_id}', [VulnerabilityController::class, 'changeStatus']);

        Route::delete('/{vulnerability_id}', [VulnerabilityController::class, 'destroy']);
    });

    Route::prefix('vulnerabilityFactors')->group(function () {

        Route::get('/', [VulnerabilityFactorController::class, 'index']);

        Route::get('/{riskFactor_id}', [VulnerabilityFactorController::class, 'show']);

        Route::get('/riskFactor/{riskFactor_id}', [VulnerabilityFactorController::class, 'getByRiskFactor']);

        Route::post('/', [VulnerabilityFactorController::class, 'store']);

        Route::put('/{riskFactor_id}', [VulnerabilityFactorController::class, 'update']);

        Route::patch('/{riskFactor_id}', [VulnerabilityFactorController::class, 'partialUpdate']);

        Route::delete('/{riskFactor_id}', [VulnerabilityFactorController::class, 'destroy']);
    });


    Route::prefix('resources')->group(function () {

        Route::get('/', [ResourceController::class, 'index']);

        Route::get('/{resource_id}', [ResourceController::class, 'show']);

        Route::get('/history/{resource_id}', [ResourceController::class, 'history']);

        Route::post('/', [ResourceController::class, 'store']);

        Route::put('/{resource_id}', [ResourceController::class, 'update']);

        Route::patch('/{resource_id}', [ResourceController::class, 'partialUpdate']);

        Route::patch('/status/{resource_id}', [ResourceController::class, 'changeStatus']);

        Route::delete('/{resource_id}', [ResourceController::class, 'destroy']);
    });

    Route::prefix('availableResources')->group(function () {

        Route::get('/', [AvailableResourceController::class, 'index']);

        Route::get('/{id}', [AvailableResourceController::class, 'show']);

        Route::get('/familyPlan/{family_plan_id}', [AvailableResourceController::class, 'getForPlan']);

        Route::post('/', [AvailableResourceController::class, 'store']);

        Route::put('/{id}', [AvailableResourceController::class, 'update']);

        Route::patch('/{id}', [AvailableResourceController::class, 'partialUpdate']);

        Route::delete('/{id}', [AvailableResourceController::class, 'destroy']);
    });

    Route::prefix('actionTypes')->group(function () {

        Route::get('/', [ActionTypeController::class, 'index']);

        Route::get('/{id}', [ActionTypeController::class, 'show']);

        Route::post('/', [ActionTypeController::class, 'store']);

        Route::put('/{id}', [ActionTypeController::class, 'update']);

        Route::delete('/{id}', [ActionTypeController::class, 'destroy']);
    });

    Route::prefix('actionPlans')->group(function () {

        Route::get('/', [ActionPlanController::class, 'index']);

        Route::get('/{id}', [ActionPlanController::class, 'show']);

        Route::get('/familyPlan/{id}', [ActionPlanController::class, 'getByPlan']);

        Route::get('/familyPlan/boolean/{id}', [ActionPlanController::class, 'getByPlanBoolean']);

        Route::post('/', [ActionPlanController::class, 'store']);

        Route::put('/{id}', [ActionPlanController::class, 'update']);

        Route::delete('/{id}', [ActionPlanController::class, 'destroy']);
    });

    Route::prefix('actionPlanActions')->group(function () {

        Route::get('/', [ActionPlanActionController::class, 'index']);

        Route::get('/{id}', [ActionPlanActionController::class, 'show']);

        Route::get('actionPlan/{id}', [ActionPlanActionController::class, 'getActionForActionPlan']);

        Route::post('/', [ActionPlanActionController::class, 'store']);

        Route::put('/{id}', [ActionPlanActionController::class, 'update']);

        Route::patch('/{id}', [ActionPlanActionController::class, 'partialUpdate']);

        Route::delete('/{id}', [ActionPlanActionController::class, 'destroy']);
    });

    Route::prefix('housingGraphics')->group(function () {

        Route::get('/', [HousingGraphicController::class, 'index']);

        Route::get('/{id}', [HousingGraphicController::class, 'show']);

        Route::get('familyPlan/{id}', [HousingGraphicController::class, 'getByFamilyPlan']);

        Route::post('/', [HousingGraphicController::class, 'store']);

        Route::patch('description/{id}', [HousingGraphicController::class, 'updateDescription']);

        Route::delete('/{id}', [HousingGraphicController::class, 'destroy']);
    });

    Route::prefix('audits')->group(function () {
        Route::get('/dashBoardAdmin', [AuditController::class, 'dashBoardAdmin']);

        Route::get('/dashBoardSupervisor', [AuditController::class, 'dashBoardSupervisor']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);

        Route::get('/user/count/{id}', [NotificationController::class, 'countUnreadByUser']);

        Route::get('/user/unread/{id}', [NotificationController::class, 'getUnreadByUser']);

        Route::get('/user/{id}', [NotificationController::class, 'getByUser']);

        Route::get('/{id}', [NotificationController::class, 'show']);

        Route::post('/', [NotificationController::class, 'store']);

        Route::put('/{id}', [NotificationController::class, 'update']);

        Route::patch('/{id}', [NotificationController::class, 'partialUpdate']);

        Route::patch('changeStatus/{id}', [NotificationController::class, 'changeStatus']);

        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });
});
