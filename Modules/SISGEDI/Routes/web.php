<?php

use Illuminate\Support\Facades\Route;
use Modules\SISGEDI\Http\Controllers\DocumentoController;

Route::prefix('sisgedi')->name('sisgedi.')->group(function () {
    Route::get('/', [DocumentoController::class, 'index'])->name('index');
    Route::get('/elementos/create', [DocumentoController::class, 'create'])->name('create');
    Route::post('/elementos', [DocumentoController::class, 'store'])->name('store');
    Route::get('/elementos/{id}/edit', [DocumentoController::class, 'edit'])->name('edit');
    Route::put('/elementos/{id}', [DocumentoController::class, 'update'])->name('update');
    Route::delete('/elementos/{id}', [DocumentoController::class, 'destroy'])->name('destroy');
});