<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TagController;

Route::get('/', [ApiController::class, 'index'])->name('apis.index');
Route::prefix('apis')->group(function () {
    Route::get('/', [ApiController::class, 'index'])->name('apis.index');
    Route::post('/', [ApiController::class, 'store'])->name('apis.store');
    Route::delete('/{apiId}/tags/{tagId}', [ApiController::class, 'detachTag'])->name('apis.detachTag');
    Route::put('/{id}', [ApiController::class, 'update'])->name('apis.update');
    Route::delete('/{id}', [ApiController::class, 'destroy'])->name('apis.destroy');
    Route::post('/{id}/execute', [ApiController::class, 'execute'])->name('apis.attachTag');
});

Route::prefix('tags')->group(function () {
    Route::get('/search', [TagController::class, 'search'])->name('tags.search');
    Route::get('/', [TagController::class, 'index'])->name('tags.index');
    Route::post('/', [TagController::class, 'store'])->name('tags.store');
    Route::put('/{id}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/{id}', [TagController::class, 'destroy'])->name('tags.destroy');
});
