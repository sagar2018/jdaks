<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PvIndex;
use Illuminate\Http\Request;

class PvController extends Controller
{
    public function index(Project $project)
    {
        $indices = $project->pvIndices()->orderBy('month', 'desc')->get();
        return view('pv.index', compact('project', 'indices'));
    }

    public function create(Project $project)
    {
        return view('pv.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'month'       => 'required|date_format:Y-m',
            'labour_idx'  => 'nullable|numeric|min:0',
            'cement_idx'  => 'nullable|numeric|min:0',
            'steel_idx'   => 'nullable|numeric|min:0',
            'bitumen_idx' => 'nullable|numeric|min:0',
            'pol_idx'     => 'nullable|numeric|min:0',
            'other_idx'   => 'nullable|numeric|min:0',
            'plant_idx'   => 'nullable|numeric|min:0',
            'base_month'  => 'nullable|date_format:Y-m',
        ]);

        $data['project_id'] = $project->id;
        $data['created_by'] = auth()->id();

        PvIndex::updateOrCreate(
            ['project_id' => $project->id, 'month' => $data['month']],
            $data
        );

        return redirect()->route('projects.pv.index', $project)->with('success', 'PV index saved.');
    }

    public function edit(Project $project, PvIndex $pv)
    {
        return view('pv.edit', compact('project', 'pv'));
    }

    public function update(Request $request, Project $project, PvIndex $pv)
    {
        $data = $request->validate([
            'labour_idx'  => 'nullable|numeric|min:0',
            'cement_idx'  => 'nullable|numeric|min:0',
            'steel_idx'   => 'nullable|numeric|min:0',
            'bitumen_idx' => 'nullable|numeric|min:0',
            'pol_idx'     => 'nullable|numeric|min:0',
            'other_idx'   => 'nullable|numeric|min:0',
            'plant_idx'   => 'nullable|numeric|min:0',
            'base_month'  => 'nullable|date_format:Y-m',
        ]);
        $pv->update($data);
        return redirect()->route('projects.pv.index', $project)->with('success', 'PV index updated.');
    }

    public function destroy(Project $project, PvIndex $pv)
    {
        $pv->delete();
        return back()->with('success', 'PV index deleted.');
    }

    public function calculate(Project $project)
    {
        $indices  = $project->pvIndices()->orderBy('month')->get();
        $boqItems = $project->boqItems()->get();
        return view('pv.calculate', compact('project', 'indices', 'boqItems'));
    }

    public function pdf(Project $project)
    {
        return back()->with('error', 'PDF export coming soon.');
    }
}
