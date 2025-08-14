<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VotePolicy
{
    /**
     * Determine whether the user can create models.
     *
     * @param Question|Answer $votable
     */
    public function create(User $user, Model $votable): bool
    {
        if (!in_array($votable::class, [Question::class, Answer::class])) {
            return false;
        }

        return $user->id !== $votable->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     * @param Question|Answer $votable
     */
    public function delete(User $user, Model $votable): bool
    {
        if (!in_array($votable::class, [Question::class, Answer::class])) {
            return false;
        }

        return $votable->votes()->whereBelongsTo($user)->exists();
    }
}
