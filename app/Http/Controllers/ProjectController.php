<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->hasRole('admin')
            ? Project::latest()->get()
            : auth()->user()->projects()->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:200',
            'package'         => 'nullable|string|max:100',
            'location'        => 'nullable|string|max:200',
            'contractor'      => 'nullable|string|max:200',
            'chainage_start'  => 'nullable|numeric|min:0',
            'chainage_end'    => 'nullable|numeric|min:0',
            'start_date'      => 'nullable|date',
            'end_date'        => 'nullable|date',
            'deadline_date'   => 'nullable|date',
            'bid_date'        => 'nullable|date',
            'bid_type'        => 'nullable|in:percentage,lump_sum',
            'bid_pct'         => 'nullable|numeric',
            'bit_grade'       => 'nullable|string|max:50',
            'dual_lane'       => 'nullable|boolean',
            'notes'           => 'nullable|string',
        ]);

        $data['created_by'] = auth()->id();
        $data['dual_lane']  = $request->boolean('dual_lane');

        $project = Project::create($data);

        // Auto-assign creator
        $project->users()->attach(auth()->id(), [
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
        ]);

        return redirect()->route('projects.hub', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return redirect()->route('projects.hub', $project);
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $data = $request->validate([
            'name'           => 'required|string|max:200',
            'package'        => 'nullable|string|max:100',
            'location'       => 'nullable|string|max:200',
            'contractor'     => 'nullable|string|max:200',
            'chainage_start' => 'nullable|numeric|min:0',
            'chainage_end'   => 'nullable|numeric|min:0',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'deadline_date'  => 'nullable|date',
            'bid_date'       => 'nullable|date',
            'bid_type'       => 'nullable|in:percentage,lump_sum',
            'bid_pct'        => 'nullable|numeric',
            'bit_grade'      => 'nullable|string|max:50',
            'dual_lane'      => 'nullable|boolean',
            'notes'          => 'nullable|string',
        ]);

        $data['dual_lane'] = $request->boolean('dual_lane');
        $project->update($data);

        return redirect()->route('projects.hub', $project)
            ->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);
        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Project deleted.');
    }

    public function hub(Project $project)
    {
        $this->authorizeProject($project);
        $project->load(['boqItems', 'billingConfig', 'raBills']);

        $stats = [
            'total_boq'     => $project->total_boq_amount,
            'earned_value'  => $project->earned_value,
            'completion'    => $project->completion_pct,
            'ra_bills'      => $project->raBills->count(),
            'pending_bills' => $project->raBills->whereIn('status', ['draft', 'submitted'])->count(),
        ];

        return view('projects.hub', compact('project', 'stats'));
    }

    private function authorizeProject(Project $project): void
    {
        if (!auth()->user()->hasRole('admin') &&
            !$project->users->contains(auth()->id())) {
            abort(403, 'You do not have access to this project.');
        }
    }
}
