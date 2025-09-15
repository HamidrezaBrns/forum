<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortParam = $request->sortParams();

        $tags = Tag::query()
            ->orderBy($sortParam['sort'], $sortParam['dir'])
            ->orderByDesc('id');;

        if ($search = $request->query('search')) {
            $tags->whereAny(['name', 'description'], 'LIKE', "%$search%");
        }

        return Inertia::render('Admin/Tags/Index', [
            'tags' => fn() => $tags->paginate(10)->withQueryString(),
            'meta' => $sortParam,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Tags/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string', 'min:10', 'max:255'],
        ]);

        Tag::create($validated);

        return to_route('admin.tags.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/Edit', [
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string', 'min:10', 'max:255'],
        ]);

        $tag->update($validated);

        return to_route('admin.tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back();
    }
}
