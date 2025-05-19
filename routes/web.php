<?php

use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get(
        'dashboard',
        function () {return redirect(route('training.questions.show'));
    })->name('dashboard');

    Route::get(
        'training/questions/{session?}/{topic?}',
        [TrainingController::class, 'showQuestionsAction']
    )->name('training.questions.show');

    Route::get(
        'training/questions/{session?}/{topic?}/watch-tutorial',
        [TrainingController::class, 'watchTutorialAction']
    )->name('training.questions.watch');

    Route::post(
        'training/questions/{session?}/{topic?}',
        [TrainingController::class, 'submitQuestionsAction']
    )->name('training.questions.submit');

    Route::get(
        'training/{session}',
        [TrainingController::class, 'showTrainingStateAction']
    )->name('training.show');
});

require __DIR__ . '/auth.php';
