<?php

namespace App\Observers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;

class VoteObserver
{
    /**
     * Handle the Vote "created" event.
     */
    public function created(Vote $vote): void
    {
        /** @var Question|Answer $votable */
        $votable = $vote->votable;

        $votable->withoutTimestamps(fn() => $votable->increment('votes_count', $vote->is_upvote ? 1 : -1));
    }

    /**
     * Handle the Vote "updated" event.
     */
    public function updated(Vote $vote): void
    {
        if ($vote->wasChanged('is_upvote')) {
            /** @var Question|Answer $votable */
            $votable = $vote->votable;

            $votable->withoutTimestamps(fn() => $votable->increment('votes_count', $vote->is_upvote ? 2 : -2));
        }
    }

    /**
     * Handle the Vote "deleted" event.
     */
    public function deleted(Vote $vote): void
    {
        /** @var Question|Answer $votable */
        $votable = $vote->votable;

        $votable->withoutTimestamps(fn() => $votable->decrement('votes_count', $vote->is_upvote ? 1 : -1));
    }
}
