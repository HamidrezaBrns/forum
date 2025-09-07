<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('view-any', Question::class);

        return inertia('Questions/Index', [
            'questions' => fn() => QuestionResource::collection(Question::with(['user', 'tags'])->latest()->latest('id')->paginate()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Question::class);

        return inertia('Questions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Question::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
            'tags' => ['nullable'],
        ]);

        $question = Question::create([
            ...Arr::except($validated, 'tags'),
            'user_id' => $request->user()->id,
        ]);

        if ($validated['tags']) {
            foreach (explode(' ', $validated['tags']) as $tag) {
                $tag = Tag::where(['name' => $tag])->first();
                $question->tags()->attach($tag);
            }
        }

        return to_route('questions.show', $question);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        Gate::authorize('view', $question);

        views($question)->cooldown(now()->addDay())->record();

        return inertia('Questions/Show', [
            'question' => fn() => QuestionResource::make($question->load(['user', 'tags']))->withAttribute('permissions')->withAttribute('user-vote'),
            'answers' => fn() => AnswerResource::collection($question->answers()->with(['user'])->orderBy('votes_count', 'desc')->latest()->latest('id')->paginate(10)),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        Gate::authorize('update', $question);

        $question->load('tags');

        return inertia('Questions/Edit', [
            'question' => fn() => QuestionResource::make($question),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        Gate::authorize('update', $question);

        $tags = array_filter(explode(' ', trim($request->tags)));
        $request->merge(['tags' => $tags]);

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'exists:tags,name'],
        ], [
            'tags.*.exists' => 'The selected tags does not exist.',
        ]);

        $question->update(Arr::except($validated, 'tags'));

        $tagIds = Tag::whereIn('name', $tags)->pluck('id')->toArray();
        $question->tags()->sync($tagIds);

        return to_route('questions.show', $question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        Gate::authorize('delete', $question);

        $question->delete();

        return to_route('questions.index');
    }

    public function tagged(Tag $tag)
    {
        return inertia('Questions/Index', [
            'tag' => $tag,
            'questions' => fn() => QuestionResource::collection($tag->questions()->with(['user', 'tags'])->latest()->latest('id')->paginate()),
        ]);
    }

    public function acceptAnswer(Question $question, Answer $answer)
    {
        Gate::authorize('accept', [$answer, $question]);
        Gate::authorize('accept-answer', [$question, $answer]);

        $question->withoutTimestamps(function () use ($question, $answer) {
            $question->update([
                'accepted_answer_id' => $question->accepted_answer_id === $answer->id ? null : $answer->id,
            ]);
        });

        return back();
    }
}
