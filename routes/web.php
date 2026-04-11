<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::resource('barangs', BarangController::class);

Route::get('/', function () {
    return view('index');
});
