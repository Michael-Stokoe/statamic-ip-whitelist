<?php

use Illuminate\Support\Facades\Route;
use Stokoe\IpWhitelist\Http\Controllers\IpWhitelistController;
use Stokoe\IpWhitelist\Http\Controllers\SettingsController;

Route::prefix('ip-whitelist')->name('ip-whitelist.')->group(function () {
    Route::get('/', [IpWhitelistController::class, 'index'])->name('index');
    Route::post('/', [IpWhitelistController::class, 'store'])->name('store');
    Route::put('/entries', [IpWhitelistController::class, 'update'])->name('update');
    Route::delete('/entries', [IpWhitelistController::class, 'destroy'])->name('destroy');
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});
