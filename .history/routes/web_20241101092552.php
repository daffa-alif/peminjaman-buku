<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRUDController;

// Route for the index page
Route::get('/', [CRUDController::class, 'index'])->name('inventory.index');

// Resource routes for CRUD operations, excluding the index route
Route::resource('crud', CRUDController::class)->except(['index']);
