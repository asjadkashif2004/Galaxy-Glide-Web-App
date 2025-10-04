<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;

/* ---------- Public (Users) ---------- */
Route::get('/', [ImageController::class, 'index'])->name('home');
Route::get('/images', [ImageController::class, 'index'])->name('images.index');
Route::get('/images/{image}', [ImageController::class, 'show'])->name('images.show');

require __DIR__.'/auth.php';

/* ---------- Dashboard redirect ---------- */
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.images.index');
    }

    return view('profile.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ---------- Admin Routes ---------- */
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/images', [ImageController::class, 'adminIndex'])->name('images.index');
        Route::get('/images/create', [ImageController::class, 'create'])->name('images.create');
        Route::post('/images', [ImageController::class, 'store'])->name('images.store');
        Route::get('/images/{image}/edit', [ImageController::class, 'edit'])->name('images.edit');
        Route::put('/images/{image}', [ImageController::class, 'update'])->name('images.update');
        Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    });

/* ---------- User Profile ---------- */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
