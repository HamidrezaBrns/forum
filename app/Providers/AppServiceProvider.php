<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use App\Observers\AnswerObserver;
use App\Observers\CommentObserver;
use App\Observers\QuestionObserver;
use App\Observers\VoteObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Model::preventLazyLoading();

        Model::unguard();

        Relation::enforceMorphMap([
            'user' => User::class,
            'question' => Question::class,
            'answer' => Answer::class,
            'comment' => Comment::class,
            'vote' => Vote::class,
        ]);

        Question::observe(QuestionObserver::class);
        Answer::observe(AnswerObserver::class);
        Comment::observe(CommentObserver::class);
        Vote::observe(VoteObserver::class);
    }
}
