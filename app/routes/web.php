<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConcessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('concessions')->name('concessions.')->group(function () {
        Route::get('/', [ConcessionController::class, 'index'])->name('index');
        Route::get('/create', [ConcessionController::class, 'create'])->name('create');
        Route::post('/', [ConcessionController::class, 'store'])->name('store');
        Route::get('/{concession}/edit', [ConcessionController::class, 'edit'])->name('edit');
        Route::post('/{concession}', [ConcessionController::class, 'update'])->name('update');
        Route::delete('/{concession}', [ConcessionController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
