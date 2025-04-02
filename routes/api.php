<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InvoiceController;

Route::get('/test', function() {
    return response()->json([
        'status' => true,
        'message' => 'API is working correctly',
        'version' => '1.0',
        'timestamp' => now()
    ]);
});
Route::get('/users/test', [UserController::class, 'test']);


///////////////////////////////////////////////////////////////////
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class,'register'])
    ->middleware('guest')
    ->name('register');
Route::post('/login', [UserController::class, 'login'])
    ->middleware('guest')
    ->name('login');
    
    Route::post('/logout', [UserController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
Route::apiResource('doctors', DoctorController::class);
Route::apiResource('patients', PatientController::class);
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('invoices', InvoiceController::class);
