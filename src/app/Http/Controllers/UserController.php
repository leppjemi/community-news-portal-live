<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required|in:user,editor,admin',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            User::create($validated);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating user: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|min:8',
                'role' => 'required|in:user,editor,admin',
            ]);

            if (! empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating user: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting user: '.$e->getMessage());

            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }
}
