<?php

use App\Http\Controllers\Admin\AnswerController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware('admin')
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('users', UserController::class)->withTrashed()->only(['index', 'show', 'destroy']);
        Route::patch('users/{user}/restore', [UserController::class, 'restore'])->withTrashed()->name('users.restore');
        Route::delete('users/{user}/force', [UserController::class, 'forceDelete'])->withTrashed()->name('users.forceDelete');

        Route::resource('questions', QuestionController::class)->withTrashed()->except(['create', 'store']);
        Route::patch('questions/{question}/restore', [QuestionController::class, 'restore'])->withTrashed()->name('questions.restore');
        Route::delete('questions/{question}/force', [QuestionController::class, 'forceDelete'])->withTrashed()->name('questions.forceDelete');
        Route::patch('questions/{question}/close', [QuestionController::class, 'close'])->name('questions.close');
        Route::patch('questions/{question}/reopen', [QuestionController::class, 'reopen'])->name('questions.reopen');

        Route::resource('answers', AnswerController::class)->withTrashed()->except(['create', 'store']);
        Route::patch('answers/{answer}/restore', [AnswerController::class, 'restore'])->withTrashed()->name('answers.restore');
        Route::delete('answers/{answer}/force', [AnswerController::class, 'forceDelete'])->withTrashed()->name('answers.forceDelete');

        Route::resource('comments', CommentController::class);

        Route::resource('tags', TagController::class);
    });
