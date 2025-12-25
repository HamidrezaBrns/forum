<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\UserResource;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user)
    {
        $activities = $user->activities()
            ->with([
                'subject' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                    Answer::class => ['question'],

                    Comment::class => ['commentable' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                        Answer::class => ['question']
                    ])],

                    Vote::class => ['votable' => fn(MorphTo $morphTo) => $morphTo->morphWith([
                        Answer::class => ['question']
                    ])],
                ])
            ]);

        return Inertia::render('Profile/Activities', [
            'user' => UserResource::make($user),
            'activities' => ActivityResource::collection($activities->latest()->paginate()),
        ]);
    }
}
