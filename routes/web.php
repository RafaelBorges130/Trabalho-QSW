<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Isso cria rotas para index, create, store, show, edit, update, destroy
Route::resource('products', ProductController::class);
