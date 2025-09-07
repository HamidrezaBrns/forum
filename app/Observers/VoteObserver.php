<?php

namespace App\Observers;

use App\Models\Activity;
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

        Activity::create([
            'user_id' => $vote->user_id,
            'subject_id' => $vote->id,
            'subject_type' => 'vote',
            'type' => 'cast_vote',
        ]);
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

        Activity::where('subject_id', $vote->id)->where('subject_type', 'vote')->delete();
    }
}
