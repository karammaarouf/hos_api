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
use App\Http\Controllers\PharmacyController;

Route::get('/test', function() {
    return response()->json([
        'status' => true,
        'message' => 'API is working correctly',
        'version' => '1.0',
        'timestamp' => now()
    ]);
});

//راوتات التسجيل
Route::post('/register', [UserController::class,'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');   
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::put('/users/{user}', [UserController::class, 'update'])->middleware('auth:sanctum');

// Add this new route for profile image upload
Route::post('/profile-image/upload', [UserController::class, 'uploadProfileImage'])->middleware('auth:sanctum');

// لعرض صورة المستخدم
Route::get('/profile-image/{filename}', function ($filename) {
    $path = storage_path('app/public/profiles/' . $filename);
    
    if (!file_exists($path)) {
        return response()->json(['message' => 'Image not found'], 404);
    }

    return response()->file($path);
});

//راوتات ادارة الاطباء
Route::apiResource('doctors', DoctorController::class);
//راوتات ادارة المرضى
Route::apiResource('patients', PatientController::class);
//راوترات ادارة الاقسام
Route::apiResource('departments', DepartmentController::class);
//راوتات ادارة المواعيد
Route::apiResource('appointments', AppointmentController::class);
//راوتات ادارة الفواتير او الصرفات
Route::apiResource('invoices', InvoiceController::class);
//راوتات ادارة الصيدلية
Route::apiResource('pharmacy', PharmacyController::class);
