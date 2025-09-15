<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Question $question): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
    {
        if ($question->isClosed()) {
            return false;
        }
        return $user->id === $question->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->id === $question->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Question $question): bool
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        return $user->is_admin;
    }

    public function close(User $user, Question $question): bool
    {
        return $user->id === $question->user_id;
    }

    public function acceptAnswer(User $user, Question $question, Answer $answer): bool
    {
        // The selected answer must belong to this question.
        if ($answer->question_id !== $question->id) {
            return false;
        }

        // User can't accept own answer  (handled in answer policy)
//        if ($answer->user_id === $user->id) {
//            return false;
//        }

        // Not allowed for closed question
        if ($question->isClosed()) {
            return false;
        }

        // Just owner of the question can accept an answer.
        return $user->id === $question->user_id;
    }
}
