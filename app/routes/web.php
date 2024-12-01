<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConcessionController;
use App\Http\Controllers\OrderController;
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

Route::middleware('auth')->group(function () {


    Route::get('/', [OrderController::class, 'index'])->name('orders.index');

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

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'view'])->name('view');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'view'])->name('view');
        Route::post('/{concession}', [OrderController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
