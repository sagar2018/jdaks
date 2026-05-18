<?php

namespace App\Http\Controllers;

use App\Models\ProgressEntry;
use App\Models\Project;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Project $project)
    {
        $entries = $project->progressEntries()
            ->with('boqItem')
            ->latest('date')
            ->paginate(30);
        return view('progress.index', compact('project', 'entries'));
    }

    public function create(Project $project)
    {
        $boqItems = $project->boqItems()->orderBy('category')->get();
        return view('progress.create', compact('project', 'boqItems'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'boq_item_id'   => 'required|exists:boq_items,id',
            'date'          => 'required|date',
            'quantity'      => 'required|numeric|min:0',
            'chainage_from' => 'nullable|numeric',
            'chainage_to'   => 'nullable|numeric',
            'status'        => 'required|in:completed,partial',
            'notes'         => 'nullable|string|max:500',
        ]);

        $data['project_id'] = $project->id;
        $data['created_by'] = auth()->id();

        ProgressEntry::create($data);

        return redirect()->route('projects.progress.index', $project)
            ->with('success', 'Progress entry recorded.');
    }

    public function edit(Project $project, ProgressEntry $entry)
    {
        $boqItems = $project->boqItems()->orderBy('category')->get();
        return view('progress.edit', compact('project', 'entry', 'boqItems'));
    }

    public function update(Request $request, Project $project, ProgressEntry $entry)
    {
        $data = $request->validate([
            'boq_item_id'   => 'required|exists:boq_items,id',
            'date'          => 'required|date',
            'quantity'      => 'required|numeric|min:0',
            'chainage_from' => 'nullable|numeric',
            'chainage_to'   => 'nullable|numeric',
            'status'        => 'required|in:completed,partial',
            'notes'         => 'nullable|string|max:500',
        ]);

        $entry->update($data);
        return redirect()->route('projects.progress.index', $project)->with('success', 'Entry updated.');
    }

    public function destroy(Project $project, ProgressEntry $entry)
    {
        $entry->delete();
        return back()->with('success', 'Entry deleted.');
    }

    public function report(Project $project)
    {
        $boqItems = $project->boqItems()->with('progressEntries')->orderBy('category')->get();
        return view('progress.report', compact('project', 'boqItems'));
    }

    public function export(Project $project)
    {
        return back()->with('error', 'Export coming soon.');
    }
}
