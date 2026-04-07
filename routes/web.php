<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\ImportController;

Route::get('/', fn() => redirect()->route('barangs.index'));

// CRUD Barang
Route::resource('barangs', BarangController::class);

// CRUD Satuan
Route::resource('satuans', SatuanController::class)->except(['show']);

// Stok — set jumlah stok per barang
Route::get('/stok',               [StokController::class, 'index'])->name('stok.index');
Route::get('/stok/{barang}/edit', [StokController::class, 'edit'])->name('stok.edit');
Route::put('/stok/{barang}',      [StokController::class, 'update'])->name('stok.update');

// Import Excel
Route::get('/import',  [ImportController::class, 'index'])->name('import.index');
Route::post('/import', [ImportController::class, 'store'])->name('import.store');