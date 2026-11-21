<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArchiveController::class, 'index'])->name('archive.home');
Route::get('/ringkasan', [ArchiveController::class, 'summary'])->name('archive.summary');
Route::get('/peta-etnografis', [ArchiveController::class, 'map'])->name('archive.map');
Route::get('/galeri-digital', [ArchiveController::class, 'gallery'])->name('archive.gallery');
Route::get('/video', [ArchiveController::class, 'video'])->name('archive.video');
Route::get('/profil-tim', [ArchiveController::class, 'team'])->name('archive.team');
Route::get('/data-situs', [ArchiveController::class, 'catalog'])->name('archive.catalog');

Route::prefix('api')->group(function () {
    Route::get('/sites', [ArchiveController::class, 'apiSites'])->name('api.sites');
    Route::get('/sites/{siteCode}', [ArchiveController::class, 'apiSiteDetail'])->name('api.sites.show');
});

// Admin Authentication & Content Management Panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::put('/account', [AdminAuthController::class, 'updateAccount'])->name('account.update');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Postingan Situs & Galeri Budaya
        Route::get('/sites/create', [AdminDashboardController::class, 'createSite'])->name('sites.create');
        Route::post('/sites', [AdminDashboardController::class, 'storeSite'])->name('sites.store');
        Route::get('/sites/{site}/edit', [AdminDashboardController::class, 'editSite'])->name('sites.edit');
        Route::put('/sites/{site}', [AdminDashboardController::class, 'updateSite'])->name('sites.update');
        Route::delete('/sites/{site}', [AdminDashboardController::class, 'destroySite'])->name('sites.destroy');

        // Postingan Video Dokumenter
        Route::post('/videos', [AdminDashboardController::class, 'storeVideo'])->name('videos.store');
        Route::put('/videos/{video}', [AdminDashboardController::class, 'updateVideo'])->name('videos.update');
        Route::delete('/videos/{video}', [AdminDashboardController::class, 'destroyVideo'])->name('videos.destroy');

        // Postingan Profil & Anggota Tim Kerja
        Route::put('/team/proposer', [AdminDashboardController::class, 'updateProposer'])->name('team.proposer.update');
        Route::post('/team/members', [AdminDashboardController::class, 'storeTeamMember'])->name('team.members.store');
        Route::put('/team/members/{member}', [AdminDashboardController::class, 'updateTeamMember'])->name('team.members.update');
        Route::delete('/team/members/{member}', [AdminDashboardController::class, 'destroyTeamMember'])->name('team.members.destroy');
    });
});
