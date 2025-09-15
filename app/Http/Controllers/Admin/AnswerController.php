<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortParam = $request->sortParams();

        $answers = Answer::query()
            ->withTrashed()
            ->with('user:id,username,email')
            ->orderBy($sortParam['sort'], $sortParam['dir'])
            ->orderByDesc('id');

        if ($search = $request->query('search')) {
            $answers->where('body', 'LIKE', "%$search%")
                ->orWhereHas('user', fn(Builder $query) => $query->whereAny(['username', 'email'], 'LIKE', "%$search%"));
        }

        return Inertia::render('Admin/Answers/Index', [
            'answers' => fn() => $answers->paginate(10)->withQueryString(),
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
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Answer $answer)
    {
        return Inertia::render('Admin/Answers/Edit', [
            'answer' => $answer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:100', 'max:10000'],
        ]);

        $answer->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        $answer->delete();

        return back();
    }

    public function forceDelete(Answer $answer)
    {
        $answer->forceDelete();

        return back();
    }

    public function restore(Answer $answer)
    {
        $answer->restore();

        return back();
    }
}
