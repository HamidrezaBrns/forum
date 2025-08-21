<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function questionComments(Question $question)
    {
        return CommentResource::collection($question->comments()->with('user')->latest()->latest('id')->get());
    }

    public function answerComments(Answer $answer)
    {
        return CommentResource::collection($answer->comments()->with('user')->latest()->latest('id')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type, int $id)
    {
        /** @var Question|Answer $commentable */
        $commentable = $this->findCommentable($type, $id);

        Gate::authorize('create', [Comment::class, $commentable]);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:10', 'max:2000'],
            'commentable_id' => ['required', 'integer'],
            'commentable_type' => ['required', 'in:question,answer'],
        ]);

        $commentable->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back();
    }

    private function findCommentable(string $type, int $id): Model
    {
        /** @var class-string<Model>|null $modelClass */
        $modelClass = Relation::getMorphedModel($type);

        abort_unless($modelClass, 404);

        return $modelClass::findOrfail($id);
    }
}
