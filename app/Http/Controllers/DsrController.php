<?php

namespace App\Http\Controllers;

use App\Models\BoqItem;
use App\Models\DsrItem;
use App\Models\Project;
use Illuminate\Http\Request;

class DsrController extends Controller
{
    public function index(Project $project)
    {
        $boqItems = $project->boqItems()->with('dsrItem')->orderBy('category')->get();
        return view('dsr.index', compact('project', 'boqItems'));
    }

    public function edit(Project $project, BoqItem $boq)
    {
        $dsr = $boq->dsrItem ?? new DsrItem([
            'project_id'        => $project->id,
            'boq_item_id'       => $boq->id,
            'material_json'     => [],
            'labour_json'       => [],
            'machinery_json'    => [],
            'deduction_pct_json'=> [],
        ]);
        return view('dsr.edit', compact('project', 'boq', 'dsr'));
    }

    public function update(Request $request, Project $project, BoqItem $boq)
    {
        $data = $request->validate([
            'material'      => 'nullable|array',
            'labour'        => 'nullable|array',
            'machinery'     => 'nullable|array',
            'deduction_pct' => 'nullable|array',
        ]);

        $mapped = [
            'material_json'      => $data['material']      ?? [],
            'labour_json'        => $data['labour']        ?? [],
            'machinery_json'     => $data['machinery']     ?? [],
            'deduction_pct_json' => $data['deduction_pct'] ?? [],
            'updated_by'         => auth()->id(),
        ];

        DsrItem::updateOrCreate(
            ['project_id' => $project->id, 'boq_item_id' => $boq->id],
            $mapped
        );

        return redirect()->route('projects.dsr.index', $project)
            ->with('success', 'DSR updated for: ' . $boq->description);
    }

    public function report(Project $project)
    {
        $items = $project->boqItems()->with('dsrItem')->orderBy('category')->get();
        return view('dsr.report', compact('project', 'items'));
    }
}
