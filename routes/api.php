<?php

use App\Http\Controllers\ContractController;
use App\Http\Controllers\FixedFeeController;
use App\Http\Controllers\TandMController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getContractData', [ContractController::class, 'getContractData']);
Route::get('/insertContractsData', [ContractController::class, 'insertContractsData']);
Route::get('/insertFixedFeeData', [FixedFeeController::class, 'insertFixedFeeData']);
Route::get('/insertTandMData', [TandMController::class, 'insertTandMData']);