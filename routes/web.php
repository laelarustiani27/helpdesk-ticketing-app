<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Teknisi\TeknisiDashboardController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeknisiController;
use App\Http\Controllers\Teknisi\TeknisiSettingsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelangganController; 

// Role routes
Route::get('/', [AuthController::class, 'showLoginForm']);

// Login routes
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/pelanggan/login',  [LoginController::class, 'showLoginPelanggan'])->name('pelanggan.login');
Route::post('/pelanggan/login', [LoginController::class, 'loginPelanggan'])->name('pelanggan.login.post');

// Notification routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin/notifications')
    ->name('admin.notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/list', [NotificationController::class, 'list'])->name('list');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/destroy-all', [NotificationController::class, 'destroyAll'])->name('destroy-all');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [AdminTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [AdminTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{id}/resolve', [AdminTicketController::class, 'resolve'])->name('tickets.resolve');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::put('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.preferences');
    Route::get('/settings/export/csv', [SettingsController::class, 'exportCsv'])->name('settings.export.csv');
    Route::get('/settings/export/month', [SettingsController::class, 'exportByMonth'])->name('settings.export.month');
    Route::post('/settings/export/backup-toggle', [SettingsController::class, 'toggleBackup'])->name('settings.export.backup-toggle');
    Route::get('/teknisi', [TeknisiController::class, 'index'])->name('teknisi.index');
    Route::get('/teknisi/create', [TeknisiController::class, 'create'])->name('teknisi.create');
    Route::post('/teknisi', [TeknisiController::class, 'store'])->name('teknisi.store');
    Route::delete('/teknisi/bulk-delete', [TeknisiController::class, 'bulkDestroy'])->name('teknisi.bulk-delete'); // ← SEBELUM {id}
    Route::get('/teknisi/{id}/edit', [TeknisiController::class, 'edit'])->name('teknisi.edit');
    Route::put('/teknisi/{id}', [TeknisiController::class, 'update'])->name('teknisi.update');
    Route::delete('/teknisi/{id}', [TeknisiController::class, 'destroy'])->name('teknisi.destroy');
    Route::delete('/riwayat/{id}', [RiwayatController::class, 'destroy'])->name('riwayat.destroy');
    Route::post('/laporan-pelanggan/{id}/approve', [AdminTicketController::class, 'approveLaporan'])->name('laporan-pelanggan.approve');
    Route::post('/laporan-pelanggan/{id}/reject',  [AdminTicketController::class, 'rejectLaporan'])->name('laporan-pelanggan.reject');
    Route::delete('/notifications/destroy-all', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
});

// Teknisi routes
Route::prefix('teknisi')->name('teknisi.')->middleware(['auth', 'role:teknisi'])->group(function () {
    Route::get('/dashboard', [TeknisiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [TeknisiDashboardController::class, 'showLaporanForm'])->name('laporan');
    Route::post('/laporan/submit', [TeknisiDashboardController::class, 'submitLaporan'])->name('laporan.submit');
    Route::get('/laporan/{id}', [TeknisiDashboardController::class, 'showLaporan'])->name('laporan.show');
    Route::get('/tugas', [TeknisiDashboardController::class, 'tugas'])->name('tugas.index');
    Route::patch('/tugas/{id}/status', [TeknisiDashboardController::class, 'updateStatus'])->name('tugas.updateStatus');
    Route::get('/tugas/{id}/komentar', [TeknisiDashboardController::class, 'getKomentar'])->name('tugas.komentar.index');
    Route::post('/tugas/{id}/komentar', [TeknisiDashboardController::class, 'storeKomentar'])->name('tugas.komentar.store');
    Route::get('/tugas/{id}/kerjakan', [TeknisiDashboardController::class, 'kerjakan'])->name('tugas.kerjakan');
    Route::get('/tugas/{id}/selesai', [TeknisiDashboardController::class, 'selesai'])->name('tugas.selesai');
    Route::get('/tugas/{id}', [TeknisiDashboardController::class, 'detailTugas'])->name('tugas.detail');
    Route::get('/riwayat', [TeknisiDashboardController::class, 'riwayat'])->name('riwayat');
    Route::get('/settings', [TeknisiSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [TeknisiDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/settings/password', [TeknisiDashboardController::class, 'updatePassword'])->name('profile.password');
    Route::put('/settings/language', [TeknisiSettingsController::class, 'updateLanguage'])->name('settings.language');
    Route::put('/settings/notifications', [TeknisiSettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/list', [NotificationController::class, 'list'])->name('notifications.list');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/destroy-all', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
    Route::delete('/riwayat/{id}', [TeknisiDashboardController::class, 'deleteRiwayat'])->name('riwayat.delete');
});

// Auction routes
Route::get('/auction/{id}', [HomeController::class, 'show'])->name('auction.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Global language & theme switcher
Route::post('/settings/language', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale', 'id');
    if (in_array($locale, ['id', 'en', 'de'])) {
        session(['locale' => $locale]);
        if (auth()->check()) {
            auth()->user()->update(['language' => $locale]);
        }
    }
    return response()->json(['success' => true]);
})->middleware('auth')->name('settings.language');

Route::post('/settings/theme', function (\Illuminate\Http\Request $request) {
    $theme = $request->input('theme', 'light');
    if (in_array($theme, ['light', 'dark'])) {
        session(['theme' => $theme]);
        if (auth()->check()) {
            auth()->user()->update(['theme' => $theme]);
        }
    }
    return response()->json(['success' => true]);
})->middleware('auth')->name('settings.theme');

Route::middleware('auth:pelanggan')->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard',       [PelangganController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan',         [PelangganController::class, 'index'])->name('laporan.index');
    Route::post('/laporan',        [PelangganController::class, 'store'])->name('laporan.store');
    Route::get('/profil',          [PelangganController::class, 'profil'])->name('profil');
    Route::put('/profil',          [PelangganController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [PelangganController::class, 'updatePassword'])->name('profil.password');
    Route::post('/logout',         [PelangganController::class, 'logout'])->name('logout');
    Route::delete('/laporan/{id}', [PelangganController::class, 'deleteLaporan'])->name('laporan.delete');
    Route::get('/laporan/download', [PelangganController::class, 'downloadLaporan'])->name('laporan.download');
});

