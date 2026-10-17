<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Client;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PublicDevisController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/renovation-maison', [PageController::class, 'renovation'])->name('renovation');
// Demander un devis nécessite un compte client (les visiteurs sont envoyés vers la connexion).
Route::post('/demande-de-devis', [PublicDevisController::class, 'store'])
    ->middleware(['auth', 'client', 'throttle:forms'])->name('devis.public.store');

Route::get('/politique-de-confidentialite', [PageController::class, 'privacy'])->name('privacy');
Route::get('/conditions-utilisation', [PageController::class, 'terms'])->name('terms');
// Adresse à déclarer dans Google Play Console (suppression de compte et des données).
Route::get('/suppression-compte', [PageController::class, 'deletion'])->name('deletion');
Route::post('/suppression-compte', [PageController::class, 'storeDeletion'])
    ->middleware('throttle:forms')->name('deletion.store');

/*
|--------------------------------------------------------------------------
| Connexion / inscription (une seule page de connexion pour tout le monde)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->middleware('throttle:forms');

    Route::get('/mot-de-passe-oublie', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [PasswordResetController::class, 'email'])
        ->middleware('throttle:forms')->name('password.email');
    Route::get('/reinitialiser/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reinitialiser', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Espace client
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'client'])->prefix('mon-espace')->name('client.')->group(function () {
    Route::get('/', [Client\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/demandes/nouvelle', [Client\DevisController::class, 'create'])->name('devis.create');
    Route::post('/demandes', [Client\DevisController::class, 'store'])->middleware('throttle:forms')->name('devis.store');
    Route::get('/demandes/{devis:reference}', [Client\DevisController::class, 'show'])->name('devis.show');
    Route::post('/demandes/{devis:reference}/messages', [Client\DevisController::class, 'message'])
        ->middleware('throttle:20,1')->name('devis.message');

    Route::get('/compte', [Client\AccountController::class, 'edit'])->name('account');
    Route::put('/compte', [Client\AccountController::class, 'update'])->name('account.update');
    Route::put('/compte/mot-de-passe', [Client\AccountController::class, 'password'])->name('account.password');
    Route::get('/compte/mes-donnees', [Client\AccountController::class, 'export'])->name('account.export');
    Route::delete('/compte', [Client\AccountController::class, 'destroy'])->name('account.destroy');
});

// Photos des demandes : stockées en privé, visibles seulement par leur auteur et l'admin.
Route::get('/photos/{photo}', [PhotoController::class, 'show'])->middleware('auth')->name('photos.show');

/*
|--------------------------------------------------------------------------
| Administration (adresse secrète, 404 pour tous les autres)
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix(config('fabara.admin_path'))->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/demandes', [Admin\DevisController::class, 'index'])->name('devis.index');
    Route::get('/demandes/{devis}', [Admin\DevisController::class, 'show'])->name('devis.show');
    Route::put('/demandes/{devis}', [Admin\DevisController::class, 'update'])->name('devis.update');
    Route::post('/demandes/{devis}/messages', [Admin\DevisController::class, 'message'])->name('devis.message');
    Route::delete('/demandes/{devis}', [Admin\DevisController::class, 'destroy'])->name('devis.destroy');

    Route::get('/services', [Admin\ServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [Admin\ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [Admin\ServiceController::class, 'update'])->name('services.update');
    Route::post('/services/{service}/deplacer/{direction}', [Admin\ServiceController::class, 'move'])
        ->whereIn('direction', ['haut', 'bas'])->name('services.move');
    Route::delete('/services/{service}', [Admin\ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/realisations', [Admin\RealisationController::class, 'index'])->name('realisations.index');
    Route::post('/realisations', [Admin\RealisationController::class, 'store'])->name('realisations.store');
    Route::put('/realisations/{realisation}', [Admin\RealisationController::class, 'update'])->name('realisations.update');
    Route::delete('/realisations/{realisation}', [Admin\RealisationController::class, 'destroy'])->name('realisations.destroy');

    Route::get('/clients', [Admin\ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{user}', [Admin\ClientController::class, 'show'])->name('clients.show');
    Route::delete('/clients/{user}', [Admin\ClientController::class, 'destroy'])->name('clients.destroy');

    Route::get('/suppressions', [Admin\DeletionRequestController::class, 'index'])->name('deletions.index');
    Route::put('/suppressions/{deletionRequest}', [Admin\DeletionRequestController::class, 'update'])->name('deletions.update');

    Route::get('/parametres', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/parametres', [Admin\SettingController::class, 'update'])->name('settings.update');
    Route::put('/parametres/mot-de-passe', [Admin\SettingController::class, 'password'])->name('settings.password');
});
