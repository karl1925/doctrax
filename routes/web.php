<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\{GoogleOAuthController, DashboardController, DocumentController, ExternalController, SettingController, ProfileController};

Route::get('/auth/google/redirect', [GoogleOAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manual', [DashboardController::class, 'manual'])->name('manual');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::get('/password/change', [PasswordController::class, 'edit'])->name('password.change');
    Route::post('/password/change', [PasswordController::class, 'update'])->name('password.update');

    Route::prefix('documents')->group(function () {
        Route::get('/forsigning', [DocumentController::class, 'forSigning'])->name('documents.forsigning');
        Route::get('/inprogress', [DocumentController::class, 'inProgress'])->name('documents.inprogress');
        Route::get('/forrevision', [DocumentController::class, 'forRevision'])->name('documents.forrevision');
        Route::get('/rejected', [DocumentController::class, 'rejected'])->name('documents.rejected');
        Route::get('/completed', [DocumentController::class, 'completed'])->name('documents.completed');
        Route::get('/history', [DocumentController::class, 'history'])->name('documents.history');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/store', [DocumentController::class, 'store'])->name('documents.store');
        Route::put('/{document}/update', [DocumentController::class, 'update'])->name('documents.update');
        Route::post('/{document}/process', [DocumentController::class, 'process'])->name('documents.process');
        Route::get('/{document}/sign', [DocumentController::class, 'sign'])->name('documents.forsigning.sign');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::get('/att/{attachment}/download', [DocumentController::class, 'download'])->name('attachments.download');
        Route::post('/att/{attachment}/replace', [DocumentController::class, 'replace'])->name('attachments.replace');
        Route::get('/att/{attachment}/preview', [DocumentController::class, 'preview'])->name('attachments.preview'); 
    });   
    Route::prefix('externals')->group(function () {
        Route::get('/recording', [ExternalController::class, 'recording'])->name('externals.recording');
        Route::get('/monitoring', [ExternalController::class, 'monitoring'])->name('externals.monitoring');
        Route::get('/endorsing', [ExternalController::class, 'endorsing'])->name('externals.endorsing');
        Route::get('/mytasks', [ExternalController::class, 'myTasks'])->name('externals.mytasks');
        Route::get('/completed', [ExternalController::class, 'completed'])->name('externals.completed');
        Route::get('/archive', [ExternalController::class, 'archive'])->name('externals.completed.archive');
        Route::get('/create', [ExternalController::class, 'create'])->name('externals.create');
        Route::post('/store', [ExternalController::class, 'store'])->name('externals.store');
        Route::get('/monitoring/{external}', [ExternalController::class, 'show'])->name('externals.monitoring.show');
        Route::get('/completed/{external}', [ExternalController::class, 'show'])->name('externals.completed.show');
        Route::get('/archive/{external}', [ExternalController::class, 'show'])->name('externals.completed.archive.show');
        Route::get('/recording/{external}/verify', [ExternalController::class, 'verify'])->name('externals.recording.verify');
        Route::get('/endorsing/{external}/verify', [ExternalController::class, 'verify'])->name('externals.endorsing.verify');
        Route::get('/mytasks/{external}/verify', [ExternalController::class, 'verify'])->name('externals.mytasks.verify');
        Route::put('/{external}/forward', [ExternalController::class, 'forward'])->name('externals.forward');
        Route::put('/{external}/endorse', [ExternalController::class, 'endorse'])->name('externals.endorse');
        Route::put('/{external}/accept', [ExternalController::class, 'accept'])->name('externals.accept');
        Route::put('/{external}/assign', [ExternalController::class, 'assign'])->name('externals.assign');
        Route::put('/{external}/complete', [ExternalController::class, 'complete'])->name('externals.complete');
        Route::get('/att/{attachment}/download', [ExternalController::class, 'download'])->name('externalatt.download');
        Route::get('/att/{attachment}/preview', [ExternalController::class, 'preview'])->name('externalatt.preview'); 
        Route::put('/{external}/addupdate', [ExternalController::class, 'addUpdate'])->name('externals.addupdate');
        Route::put('/{external}/attach', [ExternalController::class, 'addAttachment'])->name('externals.attach');
        Route::delete('/{external}/destroy', [ExternalController::class, 'destroy'])->name('externals.destroy');
        Route::patch('/externals/{external}/restore', [ExternalController::class, 'restore'])->name('externals.restore');   
    });
    Route::prefix('settings')->group(function () {
        Route::get('/organization', [SettingController::class, 'organization'])->name('settings.organization');
        Route::get('/personnel', [SettingController::class, 'personnel'])->name('settings.personnel');
        Route::delete('/personnel/{role}/delete', [SettingController::class, 'destroy'])->name('settings.personnel.destroy');
        Route::get('/preferences', [SettingController::class, 'preferences'])->name('settings.preferences');
        Route::put('/preferences/update', [SettingController::class, 'updatePreferences'])->name('settings.preferences.update');
        Route::post('/updateLeadership', [SettingController::class, 'updateLeadership'])->name('settings.updateLeadership');
        Route::post('/addUser/{role}', [SettingController::class, 'addUser'])->name('settings.addUser');
        Route::delete('/removeUser/{role}/{user}', [SettingController::class, 'removeUser'])->name('settings.removeUser');
    });
});

require __DIR__.'/auth.php';