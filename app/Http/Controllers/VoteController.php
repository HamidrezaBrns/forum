<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type, int $id)
    {
        /** @var Question|Answer $votable */
        $votable = $this->findVotable($type, $id);

        Gate::authorize('create', [Vote::class, $votable]);

        $isUpvote = $request->boolean('is_upvote');

        if ($existingVote = $votable->votes()->whereBelongsTo($request->user())->first()) {
            abort_if((bool)$existingVote->is_upvote === $isUpvote, 403, 'You already voted for this direction.');

            $existingVote->update(['is_upvote' => $isUpvote]);
        } else {
            $votable->votes()->create([
                'user_id' => $request->user()->id,
                'is_upvote' => $isUpvote
            ]);
        }

        // votes_count handled by VoteObserver

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $type, int $id)
    {
        /** @var Question|Answer $votable */
        $votable = $this->findVotable($type, $id);

        Gate::authorize('delete', [Vote::class, $votable]);

        if ($vote = $votable->votes()->whereBelongsTo($request->user())->first()) {
            $vote->delete();
        }

        return back();
    }

    private function findVotable(string $type, int $id): Model
    {
        /** @var class-string<Model>|null $modelClass */
        $modelClass = Relation::getMorphedModel($type);

        abort_unless($modelClass, 404);

        return $modelClass::findOrfail($id);
    }
}
