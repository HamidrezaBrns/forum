<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
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
            'question' => Question::class,
            'answer' => Answer::class,
            'user' => User::class,
        ]);

        Vote::observe(VoteObserver::class);
    }
}
