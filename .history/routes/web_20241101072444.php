<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\//nama controller;

Route::get('/', [BarangController::class, 'index'])->name('inventory.index');
Route::resource('barang', BarangController::class)->except(['index']);
