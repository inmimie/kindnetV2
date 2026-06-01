<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.accounts.index', compact('users'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,applicant',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Account created successfully.');
    }

    public function show(User $account)
    {
        return view('admin.accounts.show', compact('account'));
    }

    public function edit(User $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, User $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($account->id)],
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:admin,applicant',
        ]);

        $account->update($request->only('name', 'email', 'phone_number', 'role'));

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $account->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy(User $account)
    {
        if (auth()->id() === $account->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $account->delete();
        return redirect()->route('admin.accounts.index')->with('success', 'Account deleted successfully.');
    }
}
