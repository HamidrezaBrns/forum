<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortParam = $request->sortParams();

        $questions = Question::query()
            ->withTrashed()
            ->with(['user:id,username,email', 'tags:id,name'])
            ->searchPanel($request->query('search'))
            ->filterPanel($request->query('filter'))
            ->orderBy($sortParam['sort'], $sortParam['dir'])
            ->orderByDesc('id');

        return Inertia::render('Admin/Questions/Index', [
            'questions' => fn() => $questions->paginate(10)->withQueryString(),
            'meta' => $sortParam,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    public function show(Question $question)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $question->load('tags');

        return Inertia::render('Admin/Questions/Edit', [
            'tags' => Tag::select(['id', 'name'])->get(),
            'question' => $question,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:100', 'max:10000'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'exists:tags,name'],
        ], [
            'tags.*.exists' => 'The selected tags does not exist.',
        ]);

        $question->update(Arr::except($validated, 'tags'));

        $tagIds = Tag::whereIn('name', $validated['tags'] ?? [])->pluck('id')->toArray();
        $question->tags()->sync($tagIds);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        // Soft delete
        $question->delete();

        return back();
    }

    public function forceDelete(Question $question)
    {
        $question->forceDelete();

        return back();
    }

    public function restore(Question $question)
    {
        $question->restore();

        return back();
    }

    public function close(Question $question)
    {
        $question->close();

        return back();
    }

    public function reopen(Question $question)
    {
        $question->reopen();

        return back();
    }

}
