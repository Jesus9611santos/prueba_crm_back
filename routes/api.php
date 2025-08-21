<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PaymentController;

Route::get('/ping', function () {
    return response()->json(['message' => 'pong desde API']);
});

Route::prefix('places')->group(function () {
    Route::get('/', [PlaceController::class, 'index']);        // GET /api/places
    Route::get('/{id}', [PlaceController::class, 'show']);     // GET /api/places/{id}
});

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);       // POST /api/places
    Route::post('/recovery', [UserController::class, 'recovery']);       // POST /api/recoverypass
    Route::post('/login', [UserController::class, 'login']);       // POST /api/login
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/check', function () {
        return response()->json(['message' => 'Token válido']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/show', [UserController::class, 'show']); 
         Route::get('/check', function () {
            return response()->json(['message' => 'Token válido']);
        });
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);     // Listar pagos
        Route::post('/', [PaymentController::class, 'store']);    // Crear pago
        Route::get('/{id}', [PaymentController::class, 'show']);  // Ver pago
        Route::post('/{id}', [PaymentController::class, 'update']); // Actualizar pago
        Route::delete('/{id}', [PaymentController::class, 'destroy']); // Eliminar pago
    });
});
