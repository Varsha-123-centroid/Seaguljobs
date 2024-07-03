<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Api\CustomerLoginController;
use  App\Http\Controllers\Api\MDesignationController;
use  App\Http\Controllers\Api\CJobOpeningController;
use  App\Http\Controllers\Api\MLocationController;
use  App\Http\Controllers\API\RegistrationController;
use  App\Http\Controllers\API\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/customer-register', [CustomerLoginController::class, 'customer_registration']);
Route::post('/customer-login', [CustomerLoginController::class, 'customer_login']);
Route::get('/designations', [MDesignationController::class, 'getDesignation']);
Route::get('/minimumExp', [CJobOpeningController::class, 'getMinimumExp']);
Route::post('/getJobs', [CJobOpeningController::class, 'getJobs']);
Route::post('/getJobDetails', [CJobOpeningController::class, 'getJobDetails']);
Route::get('/locations', [MLocationController::class, 'getLocation']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/loginemp', [AuthController::class, 'login']);
//Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);