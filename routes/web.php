<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractHistoryController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contract routes
    Route::resource('contracts', ContractController::class);
    Route::get('/contracts-expiring', [ContractController::class, 'expiring'])->name('contracts.expiring');
    Route::get('/contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew');
    Route::put('/contracts/{contract}/process-renewal', [ContractController::class, 'processRenewal'])->name('contracts.process-renewal');

    // Contract History routes
    Route::get('/contract-histories', [ContractHistoryController::class, 'index'])->name('contract-histories.index');
    Route::get('/contract-histories/by-nik', [ContractHistoryController::class, 'byNik'])->name('contract-histories.by-nik');
    Route::get('/contract-histories/{contract}', [ContractHistoryController::class, 'show'])->name('contract-histories.show');

    // Employee routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');

    // Document Library routes
    Route::get('/documents', [App\Http\Controllers\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [App\Http\Controllers\DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [App\Http\Controllers\DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [App\Http\Controllers\DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [App\Http\Controllers\DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}', [App\Http\Controllers\DocumentController::class, 'show'])->name('documents.show');
});

require __DIR__ . '/auth.php';
