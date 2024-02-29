<?php

use App\Http\Controllers\AssociatedUsersController;
use App\Http\Controllers\MsaController;
use App\Http\Controllers\AddendumController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\UserCheckController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\FixedFeeController;
use App\Http\Controllers\TandMController;
use App\Http\Controllers\InsertController;

use App\Http\Controllers\UserNotification;
use App\Models\UserNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperionEmployeeController;
use App\Http\Controllers\OneDriveController;

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

Route::middleware('auth')->group(function () {

Route::GET('/general/notifications',[UserNotification::class,'getUserNotification']);
Route::PUT('/notification/statusupdate',[UserNotification::class,'notificationStatusUpdate']);
Route::POST('/insert/logdata',[InsertController::class,'insertData']);
Route::get('/contract/getlist/{id?}', [ContractController::class, 'getContractData']);
Route::post('/contracts/insertdata', [ContractController::class, 'insertContractsData']);
Route::post('/ff/insertFixedFeeData', [FixedFeeController::class, 'insertFixedFeeData']);
Route::post('/tm/insertTandMData', [TandMController::class, 'insertTandMData']);
Route::post('/msa/insertData', [MsaController::class, 'insertValues']);
Route::get('/msa/list', [MSAController::class, 'MSAList']);
Route::post('/user/insertuser',[UserController::class,'create']);
Route::post('/insert/experiondata', [ExperionEmployeeController::class,'store']);
Route::post('/experion/generatedata', [ExperionEmployeeController::class,'generateRandomData']);
Route::get('/experion/getexperionlist',[ExperionEmployeeController::class,'show']);
Route::post('/addeddum/insertdata', [AddendumController::class,'generateData']);
Route::post('/role/insertrole', [RoleController::class, 'insertRole']);
Route::get('/role/details', [RoleController::class, 'getRole']);
Route::post('/contracts/addcontracts', [ContractController::class,'addContract']);
Route::put('/contracts/editcontract/{id}', [ContractController::class,'updateContractData']);
Route::get('/users/getusers',[UserController::class,'getUsers']);  
Route::post('/users/adduser', [UserController::class,'addUser']);  
Route::put('/users/updateuser/{user_id}', [UserController::class,'updateUser']); 
Route::post('/add/msa', [MSAController::class, 'addMsa']);
Route::put('/update/msa/{id}', [MSAController::class, 'updateMsa']);
});

Route::get('/revenue/projection/{id?}',[RevenueController::class,'revenueProjection'])->middleware('auth');
Route::get('/notAuth',[UserCheckController::class,'notauth'])->name('notauth');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});
