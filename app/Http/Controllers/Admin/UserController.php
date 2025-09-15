<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortParam = $request->sortParams();

        $users = User::query()
            ->withTrashed()
            ->select(['id', 'username', 'email', 'name', 'is_admin', 'created_at', 'updated_at', 'deleted_at'])
            ->orderBy($sortParam['sort'], $sortParam['dir'])
            ->orderByDesc('id');

        if ($search = $request->query('search')) {
            $users->whereAny(['username', 'email', 'name'], 'LIKE', "%$search%");
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => fn() => $users->paginate()->withQueryString(),
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back();
    }

    public function forceDelete(User $user)
    {
        $user->forceDelete();

        return back();
    }

    public function restore(User $user)
    {
        $user->restore();

        return back();
    }
}
