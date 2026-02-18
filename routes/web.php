<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Admin\ListingModerationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ListingController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'provider'])
    ->name('dashboard');

// Public listings
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
// Constrain to numbers so static routes like /listings/create aren't captured by the {listing} parameter
Route::get('/listings/{listing}', [ListingController::class, 'show'])->whereNumber('listing')->name('listings.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Provider routes
Route::middleware(['auth', 'provider'])->group(function () {
    // Provider listing CRUD
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
});

// Admin moderation routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [ListingModerationController::class, 'dashboard'])->name('dashboard');

    Route::get('/listings', [ListingModerationController::class, 'index'])->name('listings.index');
    Route::post('/listings/{listing}/approve', [ListingModerationController::class, 'approve'])->name('listings.approve');
    Route::post('/listings/{listing}/reject', [ListingModerationController::class, 'reject'])->name('listings.reject');
});

require __DIR__.'/auth.php';