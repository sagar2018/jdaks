<?php

namespace App\Http\Controllers;

use App\Models\BoqItem;
use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BoqImport;

class BoqController extends Controller
{
    public function index(Project $project)
    {
        $items = $project->boqItems()->orderBy('sort_order')->orderBy('category')->paginate(50);
        $categories = $project->boqItems()->distinct()->pluck('category');
        return view('boq.index', compact('project', 'items', 'categories'));
    }

    public function create(Project $project)
    {
        return view('boq.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'category'    => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'unit'        => 'required|string|max:30',
            'quantity'    => 'required|numeric|min:0',
            'rate'        => 'required|numeric|min:0',
            'sort_order'  => 'nullable|integer',
        ]);

        $data['amount']     = $data['quantity'] * $data['rate'];
        $data['project_id'] = $project->id;

        BoqItem::create($data);

        return redirect()->route('projects.boq.index', $project)
            ->with('success', 'BOQ item added.');
    }

    public function edit(Project $project, BoqItem $boq)
    {
        return view('boq.edit', compact('project', 'boq'));
    }

    public function update(Request $request, Project $project, BoqItem $boq)
    {
        $data = $request->validate([
            'category'    => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'unit'        => 'required|string|max:30',
            'quantity'    => 'required|numeric|min:0',
            'rate'        => 'required|numeric|min:0',
            'sort_order'  => 'nullable|integer',
        ]);

        $data['amount'] = $data['quantity'] * $data['rate'];
        $boq->update($data);

        return redirect()->route('projects.boq.index', $project)
            ->with('success', 'BOQ item updated.');
    }

    public function destroy(Project $project, BoqItem $boq)
    {
        $boq->delete();
        return back()->with('success', 'BOQ item deleted.');
    }

    public function import(Request $request, Project $project)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new BoqImport($project->id), $request->file('file'));
            return back()->with('success', 'BOQ items imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function export(Project $project)
    {
        return back()->with('error', 'Export coming soon.');
    }
}
