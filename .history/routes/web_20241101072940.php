<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

Route::get('/', [CR::class, 'index'])->name('inventory.index');
Route::resource('barang', BarangController::class)->except(['index']);


//replace all barang with barang