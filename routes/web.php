<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\DetailBarangMasukController;
use App\Http\Controllers\PenawaranController;
use App\Http\Controllers\AdminPenawaranController;


// hanya bisa diakses tamu (belum login)
Route::middleware('guest')->group(function () {
    // Form login
    Route::get('/login', [LoginController::class, 'showLoginForm'])
         ->name('login');

    // Proses login
    Route::post('/login', [LoginController::class, 'login'])
         ->middleware('log.sensitive')
         ->name('login.submit');

    // Form register
    Route::get('/register', [LoginController::class, 'showRegisterForm'])
         ->name('register');

    // Proses register
    Route::post('/register', [LoginController::class, 'register'])
         ->middleware('log.sensitive')
         ->name('register.submit');
});

// Logout (method POST demi keamanan; pakai @csrf di form logout)
Route::post('/logout', [LoginController::class, 'logout'])
     ->middleware(['auth', 'log.sensitive'])
     ->name('logout');



// Profile routes untuk admin & user, tetap pakai log.sensitive
Route::middleware(['auth', 'log.sensitive'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// auth admin
Route::middleware(['auth', 'role:admin-super', 'log.sensitive'])
    ->prefix('admin-super')
    ->name('admin-super.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('kategoris', KategoriController::class);
        Route::resource('produks', ProdukController::class);
        
        // Rute Barang Masuk
        Route::resource('barang-masuk', BarangMasukController::class);
        Route::get('barang-masuk/{barangMasuk}/detail', [BarangMasukController::class, 'detail'])->name('barang-masuk.detail');
        Route::post('barang-masuk/{barangMasuk}/detail', [BarangMasukController::class, 'storeDetail'])->name('barang-masuk.store-detail');
        Route::delete('barang-masuk/detail/{detailBarangMasuk}', [BarangMasukController::class, 'destroyDetail'])->name('barang-masuk.destroy-detail');
        Route::get('barang-masuk/{barangMasuk}/finalize', [BarangMasukController::class, 'finalize'])->name('barang-masuk.finalize');
        Route::get('barang-masuk/{barangMasuk}/invoice', [BarangMasukController::class, 'invoice'])->name('barang-masuk.invoice');
        Route::post('barang-masuk/export', [BarangMasukController::class, 'exportExcel'])->name('barang-masuk.export-excel');
        
        // Rute Barang Keluar
        Route::resource('barang-keluar', BarangKeluarController::class);
        Route::get('barang-keluar/{barangKeluar}/detail', [BarangKeluarController::class, 'detail'])->name('barang-keluar.detail');
        Route::post('barang-keluar/{barangKeluar}/detail', [BarangKeluarController::class, 'storeDetail'])->name('barang-keluar.store-detail');
        Route::delete('barang-keluar/detail/{detailBarangKeluar}', [BarangKeluarController::class, 'destroyDetail'])->name('barang-keluar.destroy-detail');
        Route::get('barang-keluar/{barangKeluar}/finalize', [BarangKeluarController::class, 'finalize'])->name('barang-keluar.finalize');
        Route::get('barang-keluar/{barangKeluar}/invoice', [BarangKeluarController::class, 'invoice'])->name('barang-keluar.invoice');
        Route::post('barang-keluar/export', [BarangKeluarController::class, 'exportExcel'])->name('barang-keluar.export-excel');
        
        // Penawaran routes for admin approval
        Route::get('penawaran', [AdminPenawaranController::class, 'index'])->name('penawaran.index');
        Route::get('penawaran/{penawaran}', [AdminPenawaranController::class, 'show'])->name('penawaran.show');
        Route::get('penawaran/{penawaran}/approve', [AdminPenawaranController::class, 'showApprovalForm'])->name('penawaran.approve');
        Route::post('penawaran/{penawaran}/process-approval', [AdminPenawaranController::class, 'processApproval'])->name('penawaran.process-approval');
        Route::post('penawaran/export', [AdminPenawaranController::class, 'exportExcel'])->name('penawaran.export-excel');
        
        // Audit Log routes
        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('/audit/{auditLog}', [AuditLogController::class, 'show'])->name('audit.show');
        Route::post('/audit/export', [AuditLogController::class, 'export'])->name('audit.export');
        // Tambahkan resource lain untuk admin jika diperlukan
    });

// auth admin-gudang
Route::middleware(['auth', 'role:admin-gudang', 'log.sensitive'])
    ->prefix('admin-gudang')
    ->name('admin-gudang.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminGudangDashboard'])->name('dashboard');
        
        // Rute Barang Masuk untuk admin-gudang
        Route::resource('barang-masuk', BarangMasukController::class);
        Route::get('barang-masuk/{barangMasuk}/detail', [BarangMasukController::class, 'detail'])->name('barang-masuk.detail');
        Route::post('barang-masuk/{barangMasuk}/detail', [BarangMasukController::class, 'storeDetail'])->name('barang-masuk.store-detail');
        Route::delete('barang-masuk/detail/{detailBarangMasuk}', [BarangMasukController::class, 'destroyDetail'])->name('barang-masuk.destroy-detail');
        Route::get('barang-masuk/{barangMasuk}/finalize', [BarangMasukController::class, 'finalize'])->name('barang-masuk.finalize');
        Route::get('barang-masuk/{barangMasuk}/invoice', [BarangMasukController::class, 'invoice'])->name('barang-masuk.invoice');
        Route::post('barang-masuk/export', [BarangMasukController::class, 'exportExcel'])->name('barang-masuk.export-excel');
        
        // Rute Barang Keluar untuk admin-gudang
        Route::resource('barang-keluar', BarangKeluarController::class);
        Route::get('barang-keluar/{barangKeluar}/detail', [BarangKeluarController::class, 'detail'])->name('barang-keluar.detail');
        Route::post('barang-keluar/{barangKeluar}/detail', [BarangKeluarController::class, 'storeDetail'])->name('barang-keluar.store-detail');
        Route::delete('barang-keluar/detail/{detailBarangKeluar}', [BarangKeluarController::class, 'destroyDetail'])->name('barang-keluar.destroy-detail');
        Route::get('barang-keluar/{barangKeluar}/finalize', [BarangKeluarController::class, 'finalize'])->name('barang-keluar.finalize');
        Route::get('barang-keluar/{barangKeluar}/invoice', [BarangKeluarController::class, 'invoice'])->name('barang-keluar.invoice');
        Route::post('barang-keluar/export', [BarangKeluarController::class, 'exportExcel'])->name('barang-keluar.export-excel');
        
        // Penawaran routes for admin-gudang approval
        Route::get('penawaran', [AdminPenawaranController::class, 'index'])->name('penawaran.index');
        Route::get('penawaran/{penawaran}', [AdminPenawaranController::class, 'show'])->name('penawaran.show');
        Route::get('penawaran/{penawaran}/approve', [AdminPenawaranController::class, 'showApprovalForm'])->name('penawaran.approve');
        Route::post('penawaran/{penawaran}/process-approval', [AdminPenawaranController::class, 'processApproval'])->name('penawaran.process-approval');
        Route::post('penawaran/export', [AdminPenawaranController::class, 'exportExcel'])->name('penawaran.export-excel');
    });

// auth supervisor
Route::middleware(['auth', 'role:supervisor', 'log.sensitive'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'supervisorDashboard'])->name('dashboard');
        // Add other supervisor routes here as needed
    });

// auth sales
Route::middleware(['auth', 'role:sales', 'log.sensitive'])
    ->prefix('sales')
    ->name('sales.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'salesDashboard'])->name('dashboard');
        
        // Penawaran routes for sales
        Route::resource('penawaran', PenawaranController::class);
        Route::get('penawaran/{penawaran}/detail', [PenawaranController::class, 'detail'])->name('penawaran.detail');
        Route::post('penawaran/{penawaran}/detail', [PenawaranController::class, 'storeDetail'])->name('penawaran.store-detail');
        Route::delete('penawaran/detail/{detailPenawaran}', [PenawaranController::class, 'destroyDetail'])->name('penawaran.destroy-detail');
        Route::post('penawaran/{penawaran}/submit', [PenawaranController::class, 'submit'])->name('penawaran.submit');
        Route::get('penawaran/{penawaran}/invoice', [PenawaranController::class, 'invoice'])->name('penawaran.invoice');
        Route::post('penawaran/export', [PenawaranController::class, 'exportExcel'])->name('penawaran.export-excel');
        
        // Product data fetch routes for ajax
        Route::get('get-product/{produk}', [PenawaranController::class, 'getProduct'])->name('get-product');
        Route::get('get-product-by-code/{kode}', [PenawaranController::class, 'getProductByCode'])->name('get-product-by-code');
    });

// auth driver
Route::middleware(['auth', 'role:driver', 'log.sensitive'])
    ->prefix('driver')
    ->name('driver.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driverDashboard'])->name('dashboard');
        // Add other driver routes here as needed
    });

Route::redirect('/', '/login');
