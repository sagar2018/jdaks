<?php

namespace App\Http\Controllers;

use App\Models\InventoryMaterial;
use App\Models\InventoryTransaction;
use App\Models\Project;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Project $project)
    {
        $materials = $project->inventoryMaterials()->get();
        return view('inventory.index', compact('project', 'materials'));
    }

    public function createMaterial(Project $project)
    {
        return view('inventory.materials.create', compact('project'));
    }

    public function storeMaterial(Request $request, Project $project)
    {
        $data = $request->validate([
            'material_code' => 'nullable|string|max:50',
            'name'          => 'required|string|max:200',
            'unit'          => 'required|string|max:30',
            'reorder_qty'   => 'nullable|numeric|min:0',
            'opening_stock' => 'nullable|numeric|min:0',
        ]);
        $data['project_id'] = $project->id;
        InventoryMaterial::create($data);
        return redirect()->route('projects.inventory.index', $project)->with('success', 'Material added.');
    }

    public function editMaterial(Project $project, InventoryMaterial $mat)
    {
        return view('inventory.materials.edit', compact('project', 'mat'));
    }

    public function updateMaterial(Request $request, Project $project, InventoryMaterial $mat)
    {
        $data = $request->validate([
            'material_code' => 'nullable|string|max:50',
            'name'          => 'required|string|max:200',
            'unit'          => 'required|string|max:30',
            'reorder_qty'   => 'nullable|numeric|min:0',
            'opening_stock' => 'nullable|numeric|min:0',
        ]);
        $mat->update($data);
        return redirect()->route('projects.inventory.index', $project)->with('success', 'Material updated.');
    }

    public function destroyMaterial(Project $project, InventoryMaterial $mat)
    {
        $mat->delete();
        return back()->with('success', 'Material removed.');
    }

    public function createTxn(Project $project)
    {
        $materials = $project->inventoryMaterials()->get();
        return view('inventory.transactions.create', compact('project', 'materials'));
    }

    public function storeTxn(Request $request, Project $project)
    {
        $data = $request->validate([
            'material_id' => 'required|exists:inventory_materials,id',
            'txn_type'    => 'required|in:purchase,consumption,wastage',
            'date'        => 'required|date',
            'quantity'    => 'required|numeric|min:0.001',
            'rate'        => 'nullable|numeric|min:0',
            'notes'       => 'nullable|string|max:300',
        ]);
        $data['project_id'] = $project->id;
        $data['amount']     = ($data['quantity'] ?? 0) * ($data['rate'] ?? 0);
        $data['created_by'] = auth()->id();
        InventoryTransaction::create($data);
        return redirect()->route('projects.inventory.index', $project)->with('success', 'Transaction recorded.');
    }

    public function report(Project $project)
    {
        $materials = $project->inventoryMaterials()->with('transactions')->get();
        return view('inventory.report', compact('project', 'materials'));
    }

    public function export(Project $project)
    {
        return back()->with('error', 'Export coming soon.');
    }
}
