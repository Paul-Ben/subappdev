<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware will be applied via routes
    }

    /**
     * Display the user dashboard.
     */
    public function dashboard()
    {
        return view('user.dashboard');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->get('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->get('status') === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->get('status') === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
            'email_verified' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ]);

        // Assign roles
        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
            'email_verified' => ['boolean'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync roles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user email verification status.
     */
    public function toggleVerification(User $user)
    {
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now(),
        ]);

        $status = $user->email_verified_at ? 'verified' : 'unverified';
        
        return redirect()->back()
            ->with('success', "User email has been marked as {$status}.");
    }

    /**
     * Impersonate a user (for admin purposes).
     */
    public function impersonate(User $user)
    {
        // Store original user ID in session
        session(['impersonate_original_user' => Auth::id()]);
        
        // Login as the target user
        Auth::login($user);
        
        return redirect()->route('dashboard')
            ->with('info', "You are now impersonating {$user->name}. Click 'Stop Impersonating' to return to your account.");
    }

    /**
     * Stop impersonating and return to original user.
     */
    public function stopImpersonating()
    {
        $originalUserId = session('impersonate_original_user');
        
        if ($originalUserId) {
            $originalUser = User::find($originalUserId);
            
            if ($originalUser) {
                Auth::login($originalUser);
                session()->forget('impersonate_original_user');
                
                return redirect()->route('admin.users.index')
                    ->with('success', 'You have stopped impersonating and returned to your account.');
            }
        }
        
        return redirect()->route('dashboard')
            ->with('error', 'Unable to return to original account.');
    }
}
