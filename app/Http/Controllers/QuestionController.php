<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\DraftRequest;
use App\Http\Requests\Posts\QuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Tag;
use App\QuestionStatus;
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
            'questions' => fn() => QuestionResource::collection(
                Question::query()
                    ->with(['user', 'tags'])
                    ->whereNot('status', QuestionStatus::DRAFT)
                    ->latest()
                    ->latest('id')
                    ->paginate()
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Question::class);

        return inertia('Questions/Create', [
            'tags' => fn() => Tag::select(['id', 'name'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionRequest $request)
    {
        Gate::authorize('create', Question::class);

        $question = Question::create([
            ...Arr::except($request->validated(), 'tags'),
            'user_id' => $request->user()->id,
        ]);

        if (!empty($validated['tags'])) {
            $tagIds = Tag::whereIn('name', $validated['tags'])->pluck('id')->toArray();
            $question->tags()->sync($tagIds);
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
            'question' => fn() => QuestionResource::make($question->load(['user', 'tags']))->includeAttributes(['stats', 'permissions', 'user-vote',]),
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
            'tags' => fn() => Tag::select(['id', 'name'])->get(),
            'question' => fn() => QuestionResource::make($question),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        Gate::authorize('update', $question);

        $validated = $question->isDraft()
            ? app(DraftRequest::class)->validated()
            : app(QuestionRequest::class)->validated();

        $question->update(Arr::except($validated, 'tags'));

        $tagIds = Tag::whereIn('name', $validated['tags'] ?? [])->pluck('id')->toArray();
        $question->tags()->sync($tagIds);

        return $question->isDraft() ? back() : to_route('questions.show', $question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        Gate::authorize('delete', $question);

        $question->delete();  // soft delete

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

    public function close(Question $question)
    {
        $question->close();

        return back();
    }

    public function drafts(Request $request)
    {
        $user = $request->user();

        return inertia('Questions/Drafts', [
            'drafts' => fn() => QuestionResource::collection(
                $user->questions()
                    ->with('tags')
                    ->where('status', QuestionStatus::DRAFT)
                    ->latest()
                    ->paginate(10)
            )
        ]);
    }

    public function storeDraft(DraftRequest $request)
    {
        $question = Question::create([
            ...Arr::except($request->validated(), 'tags'),
            'status' => QuestionStatus::DRAFT,
            'user_id' => $request->user()->id,
        ]);

        if (!empty($validated['tags'])) {
            $tagIds = Tag::whereIn('name', $validated['tags'])->pluck('id')->toArray();
            $question->tags()->sync($tagIds);
        }

        return to_route('questions.show', $question);
    }

    public function publish(QuestionRequest $request, Question $question)
    {
        Gate::authorize('update', $question);
        abort_if(!$question->isDraft(), 403);

        $question->update([
            ...Arr::except($request->validated(), 'tags'),
            'status' => QuestionStatus::OPEN,
            'created_at' => now(),
//            'updated_at' => now(),
        ]);
        $tagIds = Tag::whereIn('name', $validated['tags'] ?? [])->pluck('id')->toArray();
        $question->tags()->sync($tagIds);

        return to_route('questions.show', $question);
    }

    public function destroyDraft(Question $question)
    {
        Gate::authorize('delete', $question);

        $question->forceDelete();

        return back();
    }
}
