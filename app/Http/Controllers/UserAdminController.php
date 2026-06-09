<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->visibleToAdmins()
            ->orderBy('name')
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'roleOptions' => User::assignableRoleOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(array_keys(User::assignableRoleOptions()))],
            'phone' => 'nullable|string|max:50',
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => (string) $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User account created successfully.');
    }

    public function edit(User $user)
    {
        $this->ensureManageable($user);

        return view('admin.users.edit', [
            'user' => $user,
            'roleOptions' => User::assignableRoleOptions(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->ensureManageable($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(array_keys(User::assignableRoleOptions()))],
            'phone' => 'nullable|string|max:50',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = (string) $validated['role'];
        $user->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->ensureManageable($user);

        if ((int) Auth::id() === (int) $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User account deleted successfully.');
    }

    protected function ensureManageable(User $user): void
    {
        if ($user->isSuperAdmin()) {
            abort(404);
        }
    }
}
