<?php

use App\Http\Controllers\HarvestController;
use App\Http\Controllers\LogStockController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::patch('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('harvests')->name('harvests.')->group(function () {
        Route::get('/', [HarvestController::class, 'index'])->name('index');
        Route::post('/', [HarvestController::class, 'store'])->name('store');
        Route::patch('/{harvest}', [HarvestController::class, 'update'])->name('update');
        Route::delete('/{harvest}', [HarvestController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::patch('/{transaction}', [TransactionController::class, 'update'])->name('update');
    });

    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [LogStockController::class, 'index'])->name('index');
    });

    Route::prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::patch('/{warehouse}', [WarehouseController::class, 'update'])->name('update');
    });

    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [TransactionController::class, 'indexApproval'])->name('index');
        Route::patch('/{transaction}', [TransactionController::class, 'updateApproval'])->name('update');
    });
});

Route::middleware(['role:customer'])->group(function () {

    Route::prefix('productsCustomer')->name('productsCustomer.')->group(function () {
        Route::get('/', [ProductController::class, 'indexCustomer'])->name('index');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    });



    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/history', [TransactionController::class, 'history'])->name('history.index');
        Route::post('/history/upload/{transaction}', [TransactionController::class, 'uploadPaymentProof'])->name('history.upload');
    });

});

require __DIR__.'/auth.php';
