<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Email Templates
    Route::resource('templates', EmailTemplateController::class);
    Route::post('templates/{template}/set-default', [EmailTemplateController::class, 'setDefault'])->name('templates.set-default');
    Route::post('templates/{template}/duplicate',   [EmailTemplateController::class, 'duplicate'])->name('templates.duplicate');
    Route::get('templates/{template}/preview',      [EmailTemplateController::class, 'preview'])->name('templates.preview');

    // Email preview (legacy Phase 1 routes kept)
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('preview/birthday/{employee}',    [EmailController::class, 'previewBirthday'])->name('preview.birthday');
        Route::get('preview/anniversary/{employee}', [EmailController::class, 'previewAnniversary'])->name('preview.anniversary');
        Route::get('test',   [EmailController::class, 'testForm'])->name('test');
        Route::post('test',  [EmailController::class, 'sendTest'])->name('test.send');
    });

    // Email Logs
    Route::get('logs',          [EmailLogController::class, 'index'])->name('logs.index');
    Route::get('logs/{log}',    [EmailLogController::class, 'show'])->name('logs.show');
    Route::post('logs/{log}/retry', [EmailLogController::class, 'retry'])->name('logs.retry');

    // Scheduler
    Route::get('scheduler',      [SchedulerController::class, 'index'])->name('scheduler.index');
    Route::post('scheduler/run', [SchedulerController::class, 'run'])->name('scheduler.run');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('email',  [SettingsController::class, 'email'])->name('email');
        Route::post('email', [SettingsController::class, 'updateEmail'])->name('email.update');
    });

    // Health check
    Route::get('health', [HealthController::class, 'index'])->name('health');
});
