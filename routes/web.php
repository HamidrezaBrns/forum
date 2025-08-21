<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VoteController;
use App\Http\Resources\QuestionResource;
use App\Models\Answer;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/search', SearchController::class)->name('search');

Route::middleware('auth')->group(function () {
    Route::resource('questions', QuestionController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('questions.answers', AnswerController::class)->shallow()->only(['store', 'update', 'destroy']);

    Route::post('/{type}/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/votes/{type}/{id}', [VoteController::class, 'store'])->middleware('throttle:10,1')->name('votes.store');
    Route::delete('/votes/{type}/{id}', [VoteController::class, 'destroy'])->middleware('throttle:10,1')->name('votes.destroy');
});

Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
Route::get('/questions/tagged/{tag:name}', [QuestionController::class, 'tagged'])->name('questions.tagged');

Route::get('/questions/{question}/comments', [CommentController::class, 'questionComments'])->name('questions.comments.index');
Route::get('/answers/{answer}/comments', [CommentController::class, 'answerComments'])->name('answers.comments.index');

//Route::get('test/{question}', function (\App\Models\Question $question) {
//    return Inertia::optional(fn() => $question->comments()->with('user')->latest()->paginate());
//});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
