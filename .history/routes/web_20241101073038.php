<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRUDController;

Route::get('/', [CRUDController::class, 'index'])->name('inventory.index');
Route::resource('crud', CRUDController::class)->except(['index']);


//replace all barang with barang