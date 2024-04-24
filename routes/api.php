<?php

use App\Http\Controllers\ActivityLogInsertController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Http\Controllers\MsaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OneDriveController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\UserCheckController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperionEmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!

*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/notAuth', [UserCheckController::class, 'notauth'])->name('notauth');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::middleware('auth')->group(function () {
    // Roles routes
    Route::post('/role/insertrole', [RoleController::class, 'insertRole']);
    Route::get('/role/details', [RoleController::class, 'getRole']);

    // Users routes
    Route::post('/user/insert', [UserController::class, 'create']);
    Route::get('/users/get', [UserController::class, 'getUsers']);
    Route::post('/users/add', [UserController::class, 'addUser']);
    Route::post('/group/add', [UserController::class, 'addGroup']);
    Route::put('/users/update/{user_id}', [UserController::class, 'updateUser']);

    // MSA routes
    Route::post('/msa/insertData', [MsaController::class, 'insertValues']);
    Route::get('/msa/list', [MSAController::class, 'MSAList']);
    Route::post('/msa/add/{id}', [MSAController::class, 'addMsa']);
    Route::post('/msa/update/{id}', [MSAController::class, 'editMsa']);
    Route::post('/msa/renew/{id}', [MsaController::class, 'renewMsa']);
    Route::get('/msa/count', [MSAController::class, 'msaCount']);
   Route::get('/msa/page/{id}',[MsaController::class,'msaPage']);

    // Contracts routes
    Route::post('/contracts/insertdata', [ContractController::class, 'insertContractsData']);
    Route::post('/contracts/add', [ContractController::class, 'addContract']);
    Route::post('/contracts/edit/{id}', [ContractController::class, 'updateContractData']);
    Route::get('/contracts/revenue', [ContractController::class, 'getAllContractsRevenue']);
    Route::get('/contracts/topRevenueRegions', [ContractController::class, 'topRevenueRegions']);
    Route::get('/contract/ducount', [ContractController::class, 'getDuCount']);
    Route::get('/contract/topRegions', [ContractController::class, 'getTopContractRegions']);
    Route::get('/contract/count', [ContractController::class, 'getContractCount']);

    // Revenue routes
    
    // Notifications routes
    Route::put('/notification/statusupdate', [NotificationController::class, 'notificationStatusUpdate']);
    Route::get('/notification/list', [NotificationController::class, 'getUserNotification']);

    // Experion Routes
    Route::post('/experion/insertData', [ExperionEmployeeController::class, 'store']);
    Route::post('/experion/generateData', [ExperionEmployeeController::class, 'generateRandomData']);
    Route::get('/experion/list', [ExperionEmployeeController::class, 'show']);
});



Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // Routes accessible only to super admins


});
Route::middleware(['auth', 'role:super_admin-admin'])->group(function () {
    // Routes accessible only to admins or superadmins

});

Route::get('/view-blade/{filename}', function ($filename) {
    return view($filename);
});


// MICROSOFT LOGIN
Route::post('/loginAzure', [MicrosoftAuthController::class,'loginAzure']);
Route::get('/msa/count', [MSAController::class, 'msaCount']);

Route::get('/role/details', [RoleController::class, 'getRole']);
Route::get('/contract/list/{id?}', [ContractController::class, 'getContractData']);
Route::get('/contracts/myContracts/{id}', [UserController::class, 'myContracts']);
Route::get('/revenue/list/{id?}/{msa_id?}', [RevenueController::class, 'revenueProjections']);
     
Route::post("/activitylog/insert",[ActivityLogInsertController::class,'addToActivityLog']);

// Onedrive token check
// Route::post('/onedrive', [OneDriveController::class, 'token']);
Route::post('/onedrivefile', [OneDriveController::class, 'store']);

Route::get('groups/list', [UserController::class, 'getGroups']);
Route::post('groups/assign', [UserController::class, 'assignUserGroups']);
Route::get('groups/list/users', [UserController::class, 'getGroupUsers']);
Route::get('users/list', [UserController::class, 'getUsersList']);

Route::post('groups/addUsers', [UserController::class, 'addUsersToIndividualGroup']);
Route::put('groups/removeUser', [UserController::class, 'deleteUserFromGroup']);
Route::delete('groups/delete', [UserController::class, 'deleteGroup']);