<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use App\Livewire\Forms\Builder;
use App\Livewire\Forms\PublicForm;
use App\Livewire\Forms\Results;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {
    Route::resource('forms', FormController::class);
    Route::get('forms/{form}/builder', Builder::class)->name('forms.builder');
    Route::get('forms/{form}/results', Results::class)->name('forms.results');
    Route::get('forms/{form}/export-xls', [FormController::class, 'exportXls'])->name('forms.export-xls');

    // Admin Users Management using alias
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

Route::get('f/{uuid}', PublicForm::class)
    ->name('forms.public');

require __DIR__.'/auth.php';
