<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Answer;

class AnswerObserver
{
    /**
     * Handle the Answer "created" event.
     */
    public function created(Answer $answer): void
    {
        Activity::create([
            'user_id' => $answer->user_id,
            'subject_id' => $answer->id,
            'subject_type' => 'answer',
            'type' => 'posted_answer',
        ]);
    }

    /**
     * Handle the Answer "updated" event.
     */
    public function updated(Answer $answer): void
    {
        //
    }

    /**
     * Handle the Answer "deleted" event.
     */
    public function deleted(Answer $answer): void
    {
        //
    }

    /**
     * Handle the Answer "restored" event.
     */
    public function restored(Answer $answer): void
    {
        //
    }

    /**
     * Handle the Answer "force deleted" event.
     */
    public function forceDeleted(Answer $answer): void
    {
        //
    }
}
