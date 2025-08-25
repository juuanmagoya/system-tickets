<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('supports', SupportController::class);
    Route::resource('tickets', TicketController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
