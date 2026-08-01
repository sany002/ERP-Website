<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BayController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GateLogController;
use App\Http\Controllers\Api\JobCardController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Authenticated (JWT)
    Route::middleware('auth:api')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

        // -----------------------------------------------------------
        // Phase 1: Vehicle / Garage / Workshop management
        // -----------------------------------------------------------
        Route::get('/customers', [CustomerController::class, 'index'])->middleware('permission:vehicles.view');
        Route::post('/customers', [CustomerController::class, 'store'])->middleware('permission:vehicles.create');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->middleware('permission:vehicles.view');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->middleware('permission:vehicles.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->middleware('permission:vehicles.delete');

        Route::get('/vehicles', [VehicleController::class, 'index'])->middleware('permission:vehicles.view');
        Route::post('/vehicles', [VehicleController::class, 'store'])->middleware('permission:vehicles.create');
        Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->middleware('permission:vehicles.view');
        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->middleware('permission:vehicles.update');
        Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->middleware('permission:vehicles.delete');

        Route::get('/bays', [BayController::class, 'index'])->middleware('permission:jobs.view');
        Route::post('/bays', [BayController::class, 'store'])->middleware('permission:jobs.create');
        Route::put('/bays/{bay}', [BayController::class, 'update'])->middleware('permission:jobs.update');

        Route::get('/jobs', [JobCardController::class, 'index'])->middleware('permission:jobs.view');
        Route::post('/jobs', [JobCardController::class, 'store'])->middleware('permission:jobs.create');
        Route::get('/jobs/{jobCard}', [JobCardController::class, 'show'])->middleware('permission:jobs.view');
        Route::put('/jobs/{jobCard}', [JobCardController::class, 'update'])->middleware('permission:jobs.update');
        Route::patch('/jobs/{jobCard}/status', [JobCardController::class, 'updateStatus'])->middleware('permission:jobs.update');
        Route::post('/jobs/{jobCard}/items', [JobCardController::class, 'addItem'])->middleware('permission:jobs.update');
        Route::delete('/jobs/{jobCard}/items/{itemId}', [JobCardController::class, 'removeItem'])->middleware('permission:jobs.update');
        Route::delete('/jobs/{jobCard}', [JobCardController::class, 'destroy'])->middleware('permission:jobs.delete');

        Route::get('/gate-logs', [GateLogController::class, 'index'])->middleware('permission:jobs.view');
        Route::post('/gate-logs', [GateLogController::class, 'store'])->middleware('permission:jobs.create');

        // -----------------------------------------------------------
        // Phase 2 (next): /inventory, /warehouses, /stock-transfers
        // Phase 3: /accounts, /invoices, /payments
        // Phase 4: /sales-orders, /purchase-orders
        // Phase 5: /employees, /payroll, /attendance
        // -----------------------------------------------------------
    });
});
