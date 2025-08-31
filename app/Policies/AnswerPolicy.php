<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;

class AnswerPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Question $question): bool
    {
        return $question->answers()->whereBelongsTo($user)->doesntExist();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Answer $answer): bool
    {
        return $user->id === $answer->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Answer $answer): bool
    {
        if ($user->id !== $answer->user_id) {
            return false;
        }

        return $answer->created_at->isAfter(now()->subHour());
    }

    public function accept(User $user, Answer $answer): bool
    {
        // User cannot accept own answer.
        return $user->id !== $answer->user_id;
    }
}
