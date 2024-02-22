<?php

use App\Http\Controllers\MsaController;
use App\Http\Controllers\AddendumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\FixedFeeController;
use App\Http\Controllers\TandMController;
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

Route::get('/getContractData', [ContractController::class, 'getContractData']);
Route::post('/insertContractsData', [ContractController::class, 'insertContractsData']);
Route::get('/insertFixedFeeData', [FixedFeeController::class, 'insertFixedFeeData']);
Route::get('/insertTandMData', [TandMController::class, 'insertTandMData']);
Route::post('/msa/insertData', [MsaController::class, 'insertValues']);
Route::get('/msalist', [MSAController::class, 'MSAList']);
Route::post('/insertUser',[UserController::class,'create']);
Route::post('/insert/ExperionData', [ExperionEmployeeController::class,'store']);
Route::post('/generate/ExperionData', [ExperionEmployeeController::class,'generateRandomData']);
Route::get('/display/ExperionData/{id}',[ExperionEmployeeController::class,'show']);
Route::post('/insert/AddendumData', [AddendumController::class,'generateData']);