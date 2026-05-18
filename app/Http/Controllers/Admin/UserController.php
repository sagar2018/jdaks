<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->when($request->role, fn($q) => $q->role($request->role))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles    = Role::all();
        $projects = Project::orderBy('name')->get();
        return view('admin.users.create', compact('roles', 'projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|exists:roles,name',
            'status'   => 'required|in:active,inactive',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'status'     => $data['status'],
            'created_by' => auth()->id(),
        ]);

        $user->assignRole($data['role']);

        if ($request->filled('projects')) {
            foreach ($request->projects as $projectId) {
                $user->projects()->attach($projectId, [
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully.");
    }

    public function show(User $user)
    {
        $user->load('roles', 'projects');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles    = Role::all();
        $projects = Project::orderBy('name')->get();
        $user->load('roles', 'projects');
        return view('admin.users.edit', compact('user', 'roles', 'projects'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => "required|email|unique:users,email,{$user->id}",
            'role'   => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update([
            'name'   => $data['name'],
            'email'  => $data['email'],
            'status' => $data['status'],
        ]);

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} updated.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} deleted.");
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);

        return back()->with('success', "Password reset. Temporary password: <strong>{$newPassword}</strong>");
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['status' => $user->status === 'active' ? 'inactive' : 'active']);
        $label = ucfirst($user->status);
        return back()->with('success', "User {$user->name} is now {$label}.");
    }

    public function assignProjectsForm(User $user)
    {
        $projects        = Project::orderBy('name')->get();
        $assignedIds     = $user->projects->pluck('id')->toArray();
        return view('admin.users.assign-projects', compact('user', 'projects', 'assignedIds'));
    }

    public function assignProjects(Request $request, User $user)
    {
        $request->validate(['projects' => 'array', 'projects.*' => 'exists:projects,id']);

        $sync = [];
        foreach ($request->projects ?? [] as $pid) {
            $sync[$pid] = ['assigned_by' => auth()->id(), 'assigned_at' => now()];
        }
        $user->projects()->sync($sync);

        return redirect()->route('admin.users.index')
            ->with('success', "Projects assigned to {$user->name}.");
    }
}
