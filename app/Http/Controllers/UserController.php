<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $users = User::orderBy('created_at', 'desc')->paginate(12);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('users.create');
    }

  public function store(Request $request)
{
    if (! Auth::user()->isAdmin()) {
        abort(403);
    }

    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|unique:users,email',
        'password'     => 'required|string|confirmed|min:8',
        'role'         => 'required|in:Admin,Lawyer,Client',
        'phone_number' => 'nullable|string|max:20',  // ← dagdag
        'address'      => 'nullable|string',          // ← dagdag
    ]);

    User::create([
        'name'         => $validated['name'],
        'email'        => $validated['email'],
        'password'     => Hash::make($validated['password']),
        'role'         => $validated['role'],
        'phone_number' => $validated['phone_number'] ?? null,  // ← dagdag
        'address'      => $validated['address'] ?? null,       // ← dagdag
    ]);

    return redirect()->route('users.index')->with('success', 'User created successfully.');
}

    public function lawyers()
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $lawyers = User::where('role', 'Lawyer')
            ->with(['cases' => function($query) {
                $query->with('client');
            }])
            ->get()
            ->map(function($lawyer) {
                $lawyer->clients = $lawyer->cases
                    ->pluck('client')
                    ->filter()
                    ->unique(fn($client) => Str::lower(trim($client->full_name)))
                    ->values();

                return $lawyer;
            });

        return view('users.lawyers', compact('lawyers'));
    }

    public function destroy(User $user)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('User delete failed', ['error' => $e->getMessage()]);
            return redirect()->route('users.index')->with('error', 'Failed to delete user.');
        }
    }
}
