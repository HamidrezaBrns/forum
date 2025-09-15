<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\QuestionStatus;

class AnswerPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Question $question): bool
    {
        if ($question->isClosed()) {
            return false;
        }

        // User can only answer once per question.
        return $question->answers()->whereBelongsTo($user)->doesntExist();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Answer $answer): bool
    {
        if ($user->id !== $answer->user_id) {
            return false;
        }

        return $answer->question()->select('status')->value('status') === QuestionStatus::OPEN;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Answer $answer): bool
    {
        if ($user->id !== $answer->user_id) {
            return false;
        }

        return $answer->question()->select('status')->value('status') === QuestionStatus::OPEN;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Answer $answer): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Answer $answer): bool
    {
        return $user->is_admin;
    }

    public function accept(User $user, Answer $answer): bool
    {
        // User cannot accept own answer.
        return $user->id !== $answer->user_id;
    }
}
