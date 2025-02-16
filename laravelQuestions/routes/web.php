<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

// Default homepage route
Route::get('/', function () {
    // return view('welcome');
});

// Apply Admin Middleware
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/restricted-page', function () {
        return "You have admin access!";
    })->name('admin.restricted');
});
