<?php

use App\Http\Controllers\Settings\CredentialController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/account');

    Route::get('/settings/account', [CredentialController::class, 'edit'])->name('account.edit');
    Route::patch('/settings/account', [CredentialController::class, 'updateIdentity'])->name('account.identity-update');
    Route::put('/settings/account', [CredentialController::class, 'updatePassword'])->middleware('throttle:6,1')->name('account.password-update');
    Route::delete('/settings/account', [CredentialController::class, 'destroy'])->name('account.destroy');

    Route::get('/settings/account/profile', [ProfileController::class, 'edit'])->name('account.profile.edit');
    Route::put('/settings/account/profile', [ProfileController::class, 'update'])->name('account.profile.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');
});
