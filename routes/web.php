<?php

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

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
});

require __DIR__ . '/auth.php';
