<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRUDController;

Route::get('/', [CRUDController::class, 'index'])->name('inventory.index');


//replace all barang with barang