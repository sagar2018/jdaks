<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $projects = Project::withCount(['raBills', 'boqItems'])
                ->latest()->take(10)->get();
        } else {
            $projects = $user->projects()
                ->withCount(['raBills', 'boqItems'])
                ->latest()->take(10)->get();
        }

        $stats = [
            'total_projects' => $user->hasRole('admin')
                ? Project::count()
                : $user->projects()->count(),
        ];

        return view('dashboard', compact('projects', 'stats'));
    }
}
