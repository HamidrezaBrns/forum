<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Question $question)
    {
        Gate::authorize('create', Answer::class);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        Answer::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'question_id' => $question->id,
        ]);

        return to_route('questions.show', $question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        Gate::authorize('update', $answer);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $answer->update($validated);

        return to_route('questions.show', ['question' => $answer->question_id, 'page' => $request->query('page')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Answer $answer)
    {
        Gate::authorize('delete', $answer);

        $answer->delete();

        return to_route('questions.show', ['question' => $answer->question_id, 'page' => $request->query('page')]);
    }
}
