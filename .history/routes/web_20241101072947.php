<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

Route::get('/', [CRUDController::class, 'index'])->name('inventory.index');
Route::resource('barang', CRUDController::class)->except(['index']);


//replace all barang with barang