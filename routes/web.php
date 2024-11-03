<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CRUDController;

// Route for the index page
Route::get('/', [CRUDController::class, 'index'])->name('crud.index');

// Resource routes for CRUD operations, including the index route
Route::resource('crud', CRUDController::class)->except(['index']);
