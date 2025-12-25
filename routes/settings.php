<?php

use App\Http\Controllers\Settings\CredentialSettingController;
use App\Http\Controllers\Settings\ProfileSettingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::redirect('/settings', '/settings/appearance')->name('settings');

Route::middleware('auth')->group(function () {
    Route::get('/settings/account', [CredentialSettingController::class, 'edit'])->name('account.edit');
    Route::patch('/settings/account', [CredentialSettingController::class, 'updateIdentity'])->name('account.identity-update');
    Route::put('/settings/account', [CredentialSettingController::class, 'updatePassword'])->middleware('throttle:6,1')->name('account.password-update');
    Route::delete('/settings/account', [CredentialSettingController::class, 'destroy'])->name('account.destroy');

    Route::get('/settings/account/profile', [ProfileSettingController::class, 'edit'])->name('account.profile.edit');
    Route::put('/settings/account/profile', [ProfileSettingController::class, 'update'])->name('account.profile.update');

});

Route::get('/settings/appearance', function () {
    return Inertia::render('Settings/AppearanceSetting');
})->name('appearance');

Route::get('/settings/language', function () {
    return Inertia::render('Settings/LanguageSetting');
})->name('language');

Route::post('/settings/language', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'fa'])) {
        Cookie::queue('locale', $locale, 60 * 24 * 30);
    }
    return back();
})->name('language.switch');
