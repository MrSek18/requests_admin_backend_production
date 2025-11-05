<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RepresentativeController;

use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\CompanyRepresentativeController;
use App\Http\Controllers\AdminController;

// Ruta pública de prueba
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

// Autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas con token Bearer
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/admin', function (Request $request) {
        return response()->json([
            'admin' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => 'admin',
                'created_at' => $request->user()->created_at->toISOString(),
                'dni' => $request->user()->dni ?? '',
                'phone' => $request->user()->phone ?? ''
            ]
        ]);
    });

    Route::get('/protected-route', function () {
        return response()->json(['message' => 'Esta es una ruta protegida']);
    });

    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);



    Route::get('/representatives', [RepresentativeController::class, 'index']);
    Route::get('/providers', [ProviderController::class, 'index']);
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/units', [UnitController::class, 'index']);

    Route::get('/company_representatives/{company_id}', [RepresentativeController::class, 'byCompany']);
    
    Route::post('/add_request', [RequestController::class, 'store']);
    Route::put('/admins/{id}', [AdminController::class, 'update']); 
    Route::get('/requests/{request}/orden-servicio/pdf', [ServiceOrderController::class, 'download']);
    Route::get('/requests/recent', [RequestController::class, 'recent']);

    // To add new entities
    Route::post('/units', [UnitController::class, 'store']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::post('/providers', [ProviderController::class, 'store']);
    Route::post('/representatives', [RepresentativeController::class, 'store']);

    // Relación compañía-representante
    Route::post('/company-representatives', [CompanyRepresentativeController::class, 'store']);
});
