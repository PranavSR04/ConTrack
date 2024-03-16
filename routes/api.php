<?php

use App\Http\Controllers\MsaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\UserCheckController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\FixedFeeController;
use App\Http\Controllers\TandMController;
use App\Http\Middleware\RoleMiddleware;
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
    Route::put('/users/update/{user_id}', [UserController::class, 'updateUser']);

    // MSA routes
    Route::post('/msa/insertData', [MsaController::class, 'insertValues']);
    Route::get('/msa/list', [MSAController::class, 'MSAList']);
    Route::post('/msa/add/{id}', [MSAController::class, 'addMsa']);
    Route::post('/msa/update/{id}', [MSAController::class, 'editMsa']);
    Route::post('/msa/renew/{id}', [MsaController::class,'renewMsa']);

    // Contracts routes
    Route::post('/contracts/insertdata', [ContractController::class, 'insertContractsData']);
    Route::post('/contracts/add', [ContractController::class, 'addContract']);
    Route::post('/contracts/edit/{id}', [ContractController::class, 'updateContractData']);
    Route::get('/contract/list/{id?}', [ContractController::class, 'getContractData']);
    Route::get('/contracts/myContracts/{id}', [UserController::class, 'myContracts']);
    Route::get('/contracts/revenue', [ContractController::class, 'getAllContractsRevenue']);

    Route::get('/contract/topRegions', [ContractController::class, 'getTopContractRegions']);
    Route::get('/contract/count', [ContractController::class, 'getContractCount']);

    // Revenue routes
    Route::get('/revenue/list/{id?}', [RevenueController::class, 'revenueProjections']);

    // Notifications routes
   

    Route::get('/notification/list', [NotificationController::class, 'getUserNotification']); 
    Route::put('/notification/statusupdate', [NotificationController::class, 'notificationStatusUpdate']);


    // Fixed fee route
    Route::post('/fixedFee/insert', [FixedFeeController::class, 'insertFixedFeeData']);

    // Time and material route
   
    
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

