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
Route::post('/insertContractsData', [ContractController::class, 'insertContractsData']);
Route::post('/insertFixedFeeData', [FixedFeeController::class, 'insertFixedFeeData']);
Route::post('/insertTandMData', [TandMController::class, 'insertTandMData']);
Route::post('/msa/insertData', [MsaController::class, 'insertValues']);
Route::get('/get/msalist', [MSAController::class, 'MSAList']);
Route::post('/add/msa', [MSAController::class, 'addMsa']);
Route::put('/update/msa/{id}', [MSAController::class, 'updateMsa']);
Route::post('/insertUser',[UserController::class,'create']);
Route::post('/insert/ExperionData', [ExperionEmployeeController::class,'store']);
Route::post('/generate/ExperionData', [ExperionEmployeeController::class,'generateRandomData']);
Route::get('/display/ExperionData',[ExperionEmployeeController::class,'show']);
Route::post('/insert/AddendumData', [AddendumController::class,'generateData']);
Route::post('/insertRole', [RoleController::class, 'insertRole']);
Route::get('/role/details', [RoleController::class, 'getRole']);
Route::post('/add/contracts', [ContractController::class,'addContract']);
Route::put('/updateContractData/{id}', [ContractController::class,'updateContractData']);
Route::get('/getUsers',[UserController::class,'getUsers']);  
Route::post('/addUser', [UserController::class,'addUser']);  
Route::put('/updateUser/{user_id}', [UserController::class,'updateUser']); 


});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // Routes accessible only to super admins

});
Route::middleware(['auth', 'role:super_admin-admin'])->group(function () {
    // Routes accessible only to admins or superadmins

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
