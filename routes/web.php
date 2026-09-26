<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home.public');

// Public Ecosystem Routes
Route::get('/explore', [\App\Http\Controllers\PublicController::class, 'explore'])->name('public.explore');
Route::get('/showcase', [\App\Http\Controllers\PublicController::class, 'showcase'])->name('public.showcase');
Route::get('/p/{slug}', [\App\Http\Controllers\PublicController::class, 'profile'])->name('public.profile');
Route::get('/p/{slug}/pdf', [\App\Http\Controllers\PortfolioPdfController::class, 'download'])->name('public.profile.pdf');
Route::get('/p/{slug}/pdf/preview', [\App\Http\Controllers\PortfolioPdfController::class, 'preview'])->name('public.profile.pdf_preview');
Route::get('/project/{slug}', [\App\Http\Controllers\PublicController::class, 'projectDetail'])->name('public.project_detail');
Route::post('/project/{project}/like', [\App\Http\Controllers\LikeController::class, 'toggleLike'])->name('public.project.like');

Auth::routes();

Route::get('/home', function () {
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('mahasiswa.dashboard');
})->middleware(['auth', 'check.status']);

// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin', 'check.status']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');

    // Skill Management
    Route::resource('skills', \App\Http\Controllers\Admin\SkillController::class)->names('admin.skills');

    // Project Verification
    Route::get('/verifications', [\App\Http\Controllers\Admin\ProjectVerificationController::class, 'index'])->name('admin.verifications.index');
    Route::get('/verifications/{project}', [\App\Http\Controllers\Admin\ProjectVerificationController::class, 'show'])->name('admin.verifications.show');
    Route::post('/verifications/{project}/approve', [\App\Http\Controllers\Admin\ProjectVerificationController::class, 'approve'])->name('admin.verifications.approve');
    Route::post('/verifications/{project}/reject', [\App\Http\Controllers\Admin\ProjectVerificationController::class, 'reject'])->name('admin.verifications.reject');
    Route::delete('/verifications/{project}', [\App\Http\Controllers\Admin\ProjectVerificationController::class, 'destroy'])->name('admin.verifications.destroy');

    // Showcase Management
    Route::post('/showcase/{project}', [\App\Http\Controllers\Admin\ShowcaseController::class, 'store'])->name('admin.showcase.store');
    Route::delete('/showcase/{project}', [\App\Http\Controllers\Admin\ShowcaseController::class, 'destroy'])->name('admin.showcase.destroy');

    // User Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{user}/approve', [\App\Http\Controllers\Admin\UserController::class, 'approve'])->name('admin.users.approve');
    Route::post('/users/{user}/suspend', [\App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/users/{user}/activate', [\App\Http\Controllers\Admin\UserController::class, 'activate'])->name('admin.users.activate');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');

    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit_logs.index');

    // Project Revisions
    Route::get('/revisions', [\App\Http\Controllers\Admin\ProjectRevisionController::class, 'index'])->name('admin.revisions.index');
    Route::get('/revisions/{revision}', [\App\Http\Controllers\Admin\ProjectRevisionController::class, 'show'])->name('admin.revisions.show');
    Route::post('/revisions/{revision}/approve', [\App\Http\Controllers\Admin\ProjectRevisionController::class, 'approve'])->name('admin.revisions.approve');
    Route::post('/revisions/{revision}/reject', [\App\Http\Controllers\Admin\ProjectRevisionController::class, 'reject'])->name('admin.revisions.reject');

    // Project Deletions
    Route::get('/deletions', [\App\Http\Controllers\Admin\ProjectDeletionController::class, 'index'])->name('admin.deletions.index');
    Route::post('/deletions/{project}/approve', [\App\Http\Controllers\Admin\ProjectDeletionController::class, 'approve'])->name('admin.deletions.approve');
    Route::post('/deletions/{project}/reject', [\App\Http\Controllers\Admin\ProjectDeletionController::class, 'reject'])->name('admin.deletions.reject');
});

// Mahasiswa Routes
Route::group(['prefix' => 'mahasiswa', 'middleware' => ['auth', 'role:mahasiswa', 'check.status']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('mahasiswa.dashboard');

    // Profile Management
    Route::get('/profile/edit', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'edit'])->name('mahasiswa.profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Mahasiswa\ProfileController::class, 'update'])->name('mahasiswa.profile.update');

    // Skill Management
    Route::resource('skills', \App\Http\Controllers\Mahasiswa\SkillController::class)->names('mahasiswa.skills');

    // Certificate Management
    Route::resource('certificates', \App\Http\Controllers\Mahasiswa\CertificateController::class)->names('mahasiswa.certificates');

    // Achievement Management
    Route::resource('achievements', \App\Http\Controllers\Mahasiswa\AchievementController::class)->names('mahasiswa.achievements')->except(['show']);

    // Project Management
    Route::post('projects/{project}/submit', [\App\Http\Controllers\Mahasiswa\ProjectController::class, 'submit'])->name('mahasiswa.projects.submit');
    Route::post('projects/{project}/media', [\App\Http\Controllers\Mahasiswa\ProjectController::class, 'uploadMedia'])->name('mahasiswa.projects.media.store');
    Route::delete('projects/{project}/media/{media}', [\App\Http\Controllers\Mahasiswa\ProjectController::class, 'destroyMedia'])->name('mahasiswa.projects.media.destroy');
    
    // Project Revisions & Deletions
    Route::get('projects/{project}/revision', [\App\Http\Controllers\Mahasiswa\ProjectController::class, 'createRevision'])->name('mahasiswa.projects.revision.create');
    Route::post('projects/{project}/revision', [\App\Http\Controllers\Mahasiswa\ProjectController::class, 'storeRevision'])->name('mahasiswa.projects.revision.store');
    Route::post('projects/{project}/request-delete', [\App\Http\Controllers\Mahasiswa\ProjectController::class, 'requestDelete'])->name('mahasiswa.projects.request_delete');

    Route::resource('projects', \App\Http\Controllers\Mahasiswa\ProjectController::class)->names('mahasiswa.projects');

    // QR Portfolio Generator
    Route::post('/qr/generate', [\App\Http\Controllers\Mahasiswa\QrController::class, 'generate'])->name('mahasiswa.qr.generate');

    // Portfolio PDF Generator
    Route::get('/portfolio/pdf', [\App\Http\Controllers\PortfolioPdfController::class, 'downloadOwn'])->name('mahasiswa.portfolio.pdf');
    Route::get('/portfolio/pdf/preview', [\App\Http\Controllers\PortfolioPdfController::class, 'previewOwn'])->name('mahasiswa.portfolio.pdf_preview');
});
