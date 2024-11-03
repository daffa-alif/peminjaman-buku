<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\//;

Route::get('/', [BarangController::class, 'index'])->name('inventory.index');
Route::resource('barang', BarangController::class)->except(['index']);
