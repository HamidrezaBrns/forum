<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileSettingController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Settings/Account/ProfileSetting');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
        ]);

        $user = $request->user();

        if ($request->removeAvatar === true) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $user->avatar = null;
            }

        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->avatar->store('avatars', 'public');
        }

        $user->update($validated);

        return back();
    }
}
