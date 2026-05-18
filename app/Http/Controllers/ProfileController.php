<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('roles')->loadCount('votes');
        $lastActivityAt = DB::table('sessions')
            ->where('user_id', $user->user_id)
            ->max('last_activity');

        return view('cyber.profile', [
            'user' => $user,
            'canEditProfile' => true,
            'lastActivityAt' => $lastActivityAt,
        ]);
    }

    /**
     * Display a user's profile for admins.
     */
    public function show(User $user): View
    {
        $user->load(['roles'])->loadCount('votes');
        $lastActivityAt = DB::table('sessions')
            ->where('user_id', $user->user_id)
            ->max('last_activity');

        return view('cyber.profile', [
            'user' => $user,
            'canEditProfile' => false,
            'lastActivityAt' => $lastActivityAt,
        ]);
    }

    /**
     * Display a user's editable profile for admins.
     */
    public function editUser(User $user): View
    {
        $user->load(['roles'])->loadCount('votes');
        $lastActivityAt = DB::table('sessions')
            ->where('user_id', $user->user_id)
            ->max('last_activity');

        return view('cyber.profile', [
            'user' => $user,
            'canEditProfile' => true,
            'isAdminEditingUser' => true,
            'roles' => Role::query()->orderBy('name')->pluck('name'),
            'lastActivityAt' => $lastActivityAt,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('dashboard.profile')->with('status', 'profile-updated');
    }

    /**
     * Update a user's profile information as an admin.
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($user->user_id, 'user_id'),
            ],
            'is_active' => ['required', 'boolean'],
            'role' => ['nullable', 'string', Rule::exists('roles', 'name')],
        ]);

        DB::transaction(function () use ($request, $user, $validated) {
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            if ($request->user()->isNot($user)) {
                $user->is_active = (bool) $validated['is_active'];
            }

            $user->save();

            if ($request->user()->isNot($user) && ! empty($validated['role'])) {
                $user->syncRoles([$validated['role']]);
            }
        });

        return Redirect::route('dashboard.admin', ['tab' => 'users'])->with('success', 'User updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Update a user's password as an admin.
     */
    public function updateUserPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('dashboard.admin', ['tab' => 'users'])->with('success', 'User password updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Delete a user account as an admin.
     */
    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors([
                'user' => 'Use your profile page to delete your own account.',
            ]);
        }

        try {
            $user->delete();
        } catch (\Throwable $exception) {
            Log::error('Failed to delete user from admin dashboard.', [
                'user_id' => $user->user_id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'user' => 'The user could not be deleted.',
            ]);
        }

        return Redirect::route('dashboard.admin', ['tab' => 'users'])->with('success', 'User deleted successfully.');
    }
}
