<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\SubmissionController;

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


// Route::post('/submit-form', [SubmissionController::class, 'submitForm'])->name('submit-form');
Route::match(['get', 'post'], '/submit-form', [SubmissionController::class, 'submitForm'])->name('submit-form');


