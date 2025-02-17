<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\ModificationReceivedController;
use App\Http\Controllers\API\NeedMoreInfoController;
use App\Http\Controllers\API\PatientCaseController;
use App\Http\Controllers\API\PendingApprovalController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\StepFileReadyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TeamsController;
use App\Http\Controllers\API\CasePlansController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ExternalPatientCaseController;
use App\Http\Controllers\API\SubClientController;
use App\Http\Controllers\API\FileUploadController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\SoftwareController;
use App\Models\Permission;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('password/update', [AuthController::class, 'update_password']);
    Route::get('admin/dashboard', [DashboardController::class, 'adminDashboard']);

    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index');
        Route::post('users/store', 'store');
        Route::get('users/{guid}', 'detail');
        Route::post('users/update/{guid}', 'update');
        Route::delete('users/{guid}', 'destroy');
        Route::get('user/treatment-planners', 'treatmentPlanners');
        Route::get('user/treatment-planners-quality-check', 'treatmentPlannersQualityCheck');

        Route::post('user/password-update', 'passwordUpdate');
        Route::post('user/profile-pic-update', 'updateUserProfilePic');
        Route::get('update-full-name', 'updateFullName');
    });

    Route::controller(SubClientController::class)->group(function () {
        Route::get('sub_clients', 'index');
        Route::post('sub_clients/store', 'store');
        Route::get('sub_clients/{guid}', 'detail');
        Route::post('sub_clients/update/{guid}', 'update');
        Route::delete('sub_clients/{guid}', 'destroy');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index');
        Route::post('roles/store', 'store');
        Route::get('roles/{guid}', 'detail');
        Route::post('roles/update/{guid}', 'update');
        Route::delete('roles/{guid}', 'destroy');
    });
    Route::controller(PermissionController::class)->group(function () {
        Route::get('permissions', 'index');
        Route::post('permissions/store', 'store');
        Route::get('permissions/{guid}', 'detail');
        Route::post('permissions/update/{guid}', 'update');
        Route::delete('permissions/{guid}', 'destroy');
    });

    Route::controller(PatientCaseController::class)->group(function () {
        Route::get('patient_cases', 'index');
        Route::get('patient_cases/export_cases', 'export_cases');
        Route::post('patient_cases/store', 'store');
        Route::get('patient_cases/{guid}', 'detail');
        Route::post('patient_cases/update/{guid}', 'update');
        Route::delete('patient_cases/{guid}', 'destroy');
        Route::post('patient_cases/case_assign_to', 'case_assign_to');
        Route::post('patient_cases/update_patient_case_status', 'update_patient_case_status');
        Route::get('patient_cases/by_case_status/{status}', 'getCasesByStatus');
        Route::get('patient_cases/verified_by_client/{guid}', 'verified_by_client');
        Route::get('patient_case/completed_cases', 'completed_cases');
        Route::get('patient_case/cases_histories', 'casesHistories');
        Route::get('cases_histories', 'cases_histories');
        Route::get('check_case_id/{case_id}', 'checkCaseIdUnique');
        Route::get('timeupdate', 'updateStartDateTimeInString');
        Route::post('patient_case/update_status_workload', 'store_case_status_workload');
    });

    Route::controller(PendingApprovalController::class)->group(function () {
        Route::get('pending_approvals', 'index');
        Route::post('pending_approvals/store', 'store');
        Route::get('pending_approvals/{guid}', 'detail');
        Route::post('pending_approvals/update/{guid}', 'update');
        Route::delete('pending_approvals/{guid}', 'destroy');
    });

    Route::controller(ModificationReceivedController::class)->group(function () {
        Route::get('modification_receiveds', 'index');
        Route::post('modification_receiveds/store', 'store');
        Route::get('modification_receiveds/{guid}', 'detail');
        Route::post('modification_receiveds/update/{guid}', 'update');
        Route::delete('modification_receiveds/{guid}', 'destroy');
    });

    Route::controller(NeedMoreInfoController::class)->group(function () {
        Route::get('need_more_infos', 'index');
        Route::post('need_more_infos/store', 'store');
        Route::get('need_more_infos/{guid}', 'detail');
        Route::post('need_more_infos/update/{guid}', 'update');
        Route::delete('need_more_infos/{guid}', 'destroy');
    });

    Route::controller(StepFileReadyController::class)->group(function () {
        Route::get('step_file_readys', 'index');
        Route::post('step_file_readys/store', 'store');
        Route::get('step_file_readys/{guid}', 'detail');
        Route::post('step_file_readys/update/{guid}', 'update');
        Route::delete('step_file_readys/{guid}', 'destroy');
    });

    Route::controller(TeamsController::class)->group(function () {
        Route::get('teams', 'index');
        Route::post('teams/store', 'store');
        Route::get('teams/{guid}', 'detail');
        Route::post('teams/update/{guid}', 'update');
        Route::delete('teams/{guid}', 'destroy');
        Route::post('team/assign-teams', 'assignUserToTeams');
        Route::get('team/get-teams-detail/{team_id}', 'get_teams_detail');
        Route::post('team/remove-teams', 'removeTeamFromUser');
    });

    Route::controller(CasePlansController::class)->group(function () {
        Route::get('case_plans', 'index');
        Route::post('case_plans/store', 'store');
        Route::get('case_plans/{guid}', 'detail');
        Route::post('case_plans/update/{guid?}', 'update');
        Route::delete('case_plans/{guid}', 'destroy');
    });



    Route::controller(NotificationController::class)->group(function () {
        Route::get('notifications', 'index');
        Route::post('notifications/store', 'store');
        Route::get('notifications/{guid}', 'detail');

        Route::post('notifications_read_all', 'readAllUnReadNotifications');
    });

    Route::controller(SoftwareController::class)->group(function () {
        Route::get('softwares', 'index');
        Route::post('softwares/store', 'store');
        Route::get('softwares/{guid}', 'detail');
        Route::post('softwares/update/{guid}', 'update');
        Route::delete('softwares/{guid}', 'destroy');
    });

    Route::controller(ExternalPatientCaseController::class)->group(function () {
        Route::get('external_patient_cases', 'index');
        Route::post('external_patient_cases/store', 'store');
        Route::delete('external_patient_cases/{guid}', 'destroy');
        Route::get('external_patient_cases/{guid}', 'detail');
        Route::post('external_patient_cases/update/{guid}', 'update');
    });

    
});

Route::post('upload', [FileUploadController::class, 'uploadFile']);

Route::get('php-info', function () {
    $role = Role::find(1);
    $role->givePermissionTo(Permission::all());

    echo phpinfo();
});

Route::post('/upload-chunk', [FileUploadController::class, 'uploadChunk']);
Route::post('/finalize-upload', [FileUploadController::class, 'finalizeUpload']);
