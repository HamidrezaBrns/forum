<?php

use App\Http\Controllers\Settings\CredentialSettingController;
use App\Http\Controllers\Settings\ProfileSettingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/account');

    Route::get('/settings/account', [CredentialSettingController::class, 'edit'])->name('account.edit');
    Route::patch('/settings/account', [CredentialSettingController::class, 'updateIdentity'])->name('account.identity-update');
    Route::put('/settings/account', [CredentialSettingController::class, 'updatePassword'])->middleware('throttle:6,1')->name('account.password-update');
    Route::delete('/settings/account', [CredentialSettingController::class, 'destroy'])->name('account.destroy');

    Route::get('/settings/account/profile', [ProfileSettingController::class, 'edit'])->name('account.profile.edit');
    Route::put('/settings/account/profile', [ProfileSettingController::class, 'update'])->name('account.profile.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('Settings/AppearanceSetting');
    })->name('appearance');
});
