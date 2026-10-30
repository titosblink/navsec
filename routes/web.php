<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AcrController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MedicalController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    // Forgot / Reset password
    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password',   [ForgotPasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password',  [ForgotPasswordController::class, 'reset'])->name('password.update');
    // 2FA verification
    Route::get('/2fa/verify',  [AuthController::class, 'show2fa'])->name('2fa.verify');
    Route::post('/2fa/verify', [AuthController::class, 'verify2fa'])->name('2fa.verify.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Officers — specific routes MUST come before the resource to avoid {officer} swallowing them
    Route::get('/officers/search',                     [OfficerController::class, 'search'])->name('officers.search');
    Route::get('/officers/import',                     [OfficerController::class, 'importForm'])->name('officers.import.form');
    Route::post('/officers/import',                    [OfficerController::class, 'import'])->name('officers.import');
    Route::get('/officers/import/template',            [OfficerController::class, 'downloadTemplate'])->name('officers.import.template');
    Route::get('/officers/compare',                    [OfficerController::class, 'compare'])->name('officers.compare');
    Route::post('/officers/bulk-action',               [OfficerController::class, 'bulkAction'])->name('officers.bulk-action');
    Route::get('/officers/{officer}/id-card',          [OfficerController::class, 'idCard'])->name('officers.id-card');
    Route::get('/officers/{officer}/timeline',         [OfficerController::class, 'timeline'])->name('officers.timeline');
    Route::get('/officers/{officer}/pdf',              [OfficerController::class, 'downloadPdf'])->name('officers.pdf');
    Route::patch('/officers/{officer}/quick-update',   [OfficerController::class, 'quickUpdate'])->name('officers.quick-update');
    Route::resource('officers', OfficerController::class);

    // Officer sub-resources (nested under officer profile)
    Route::get('/officers/{officer}/promote',          [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('/officers/{officer}/promote',         [PromotionController::class, 'store'])->name('promotions.store');
    Route::delete('/promotions/{promotion}',           [PromotionController::class, 'destroy'])->name('promotions.destroy');

    Route::get('/officers/{officer}/acr/create',       [AcrController::class, 'create'])->name('acrs.create');
    Route::post('/officers/{officer}/acr',             [AcrController::class, 'store'])->name('acrs.store');
    Route::delete('/acrs/{acr}',                       [AcrController::class, 'destroy'])->name('acrs.destroy');

    Route::get('/officers/{officer}/discipline/create',[DisciplineController::class, 'create'])->name('disciplines.create');
    Route::post('/officers/{officer}/discipline',      [DisciplineController::class, 'store'])->name('disciplines.store');
    Route::delete('/disciplines/{discipline}',         [DisciplineController::class, 'destroy'])->name('disciplines.destroy');

    Route::get('/officers/{officer}/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/officers/{officer}/documents',       [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}',             [DocumentController::class, 'destroy'])->name('documents.destroy');

    Route::get('/officers/{officer}/medical/create',   [MedicalController::class, 'create'])->name('medicals.create');
    Route::post('/officers/{officer}/medical',         [MedicalController::class, 'store'])->name('medicals.store');
    Route::delete('/medicals/{medical}',               [MedicalController::class, 'destroy'])->name('medicals.destroy');

    Route::get('/officers/{officer}/qualification/create', [QualificationController::class, 'create'])->name('qualifications.create');
    Route::post('/officers/{officer}/qualification',       [QualificationController::class, 'store'])->name('qualifications.store');
    Route::delete('/qualifications/{qualification}',       [QualificationController::class, 'destroy'])->name('qualifications.destroy');

    // Leave (standalone index + create from officer)
    Route::get('/leaves',                              [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/export',                       [LeaveController::class, 'export'])->name('leaves.export');
    Route::get('/leaves/create',                       [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves',                             [LeaveController::class, 'store'])->name('leaves.store');
    Route::patch('/leaves/{leave}',                    [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leave}',                   [LeaveController::class, 'destroy'])->name('leaves.destroy');

    // Appointments
    Route::resource('appointments', AppointmentController::class)->except(['show']);

    // Courses
    Route::resource('courses', CourseController::class)->except(['show']);

    // Ships
    Route::resource('ships', ShipController::class)->except(['show']);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',               [ReportController::class, 'index'])->name('index');
        Route::get('/officers',       [ReportController::class, 'officers'])->name('officers');
        Route::get('/officers/export',[ReportController::class, 'exportOfficers'])->name('officers.export');
        Route::get('/postings',       [ReportController::class, 'postings'])->name('postings');
        Route::get('/postings/export',[ReportController::class, 'exportPostings'])->name('postings.export');
        Route::get('/courses',        [ReportController::class, 'courses'])->name('courses');
        Route::get('/courses/export', [ReportController::class, 'exportCourses'])->name('courses.export');
        Route::get('/seniority',        [ReportController::class, 'seniority'])->name('seniority');
        Route::get('/seniority/export', [ReportController::class, 'exportSeniority'])->name('seniority.export');
        Route::get('/statistics',                     [ReportController::class, 'statistics'])->name('statistics');
        Route::get('/retirement',                     [ReportController::class, 'retirement'])->name('retirement');
        Route::get('/promotion-eligibility',          [ReportController::class, 'promotionEligibility'])->name('promotion-eligibility');
        Route::get('/promotion-eligibility/export',   [ReportController::class, 'exportPromotionEligibility'])->name('promotion-eligibility.export');
    });

    // Settings (admin only)
    Route::get('/settings',  [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Notifications API
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Audit trail (admin only)
    Route::get('/audit-log',        [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit-log/export', [AuditController::class, 'export'])->name('audit.export');

    // Profile
    Route::get('/profile/password',  [ProfileController::class, 'showChangePassword'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.update');

    // Users (admin only)
    Route::resource('users', UserController::class)->except(['show']);
});
