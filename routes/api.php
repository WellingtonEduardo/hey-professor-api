<?php

use App\Http\Controllers\{Auth, Question};
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::post('/register', Auth\RegisterController::class)->name('register');
    Route::post('/login', Auth\LoginController::class)->name('login');
});

Route::post('/logout', Auth\LogoutController::class)
    ->middleware(['web', 'auth'])
    ->name('logout');
Route::middleware(['auth:sanctum', StartSession::class])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/questions', Question\StoreController::class)->name('questions.store');
    Route::put('/questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('/questions/{question}', Question\DestroyController::class)->name('questions.destroy');
    Route::delete('/questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');
    Route::put('/questions/{question}/restore', Question\RestoreController::class)->name('questions.restore');
    Route::put('/questions/{question}/publish', Question\PublishController::class)->name('questions.publish');

});
