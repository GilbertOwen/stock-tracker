<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\HistoriStokController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\StokMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('barangs.index'));

// CRUD Barang
Route::resource('barangs', BarangController::class);

// CRUD Satuan
Route::resource('satuans', SatuanController::class)->except(['show']);

// Stok — read-only (perubahan via Stok Masuk / Stok Keluar)
Route::get('/stok', [StokController::class, 'index'])->name('stok.index');

// Stok Masuk
Route::get('/stok-masuk',         [StokMasukController::class, 'index'])->name('stok-masuk.index');
Route::get('/stok-masuk/create',  [StokMasukController::class, 'create'])->name('stok-masuk.create');
Route::post('/stok-masuk',        [StokMasukController::class, 'store'])->name('stok-masuk.store');

// Stok Keluar
Route::get('/stok-keluar',        [StokKeluarController::class, 'index'])->name('stok-keluar.index');
Route::get('/stok-keluar/create', [StokKeluarController::class, 'create'])->name('stok-keluar.create');
Route::post('/stok-keluar',       [StokKeluarController::class, 'store'])->name('stok-keluar.store');

// Histori Stok
Route::get('/histori-stok', [HistoriStokController::class, 'index'])->name('histori-stok.index');

// Import Excel
Route::get('/import',  [ImportController::class, 'index'])->name('import.index');
Route::post('/import', [ImportController::class, 'store'])->name('import.store');
