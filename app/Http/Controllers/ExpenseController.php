<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Project $project)
    {
        $expenses = $project->expenses()->latest('date')->paginate(30);
        $totals   = [
            'labour'   => $project->expenses()->where('category', 'labour')->sum('amount'),
            'machinery'=> $project->expenses()->where('category', 'machinery')->sum('amount'),
            'other'    => $project->expenses()->where('category', 'other')->sum('amount'),
        ];
        return view('expenses.index', compact('project', 'expenses', 'totals'));
    }

    public function create(Project $project)
    {
        return view('expenses.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'date'        => 'required|date',
            'category'    => 'required|in:labour,machinery,other',
            'description' => 'required|string|max:300',
            'amount'      => 'required|numeric|min:0',
        ]);
        $data['project_id'] = $project->id;
        $data['created_by'] = auth()->id();
        Expense::create($data);
        return redirect()->route('projects.expenses.index', $project)->with('success', 'Expense recorded.');
    }

    public function edit(Project $project, Expense $exp)
    {
        return view('expenses.edit', compact('project', 'exp'));
    }

    public function update(Request $request, Project $project, Expense $exp)
    {
        $data = $request->validate([
            'date'        => 'required|date',
            'category'    => 'required|in:labour,machinery,other',
            'description' => 'required|string|max:300',
            'amount'      => 'required|numeric|min:0',
        ]);
        $exp->update($data);
        return redirect()->route('projects.expenses.index', $project)->with('success', 'Expense updated.');
    }

    public function destroy(Project $project, Expense $exp)
    {
        $exp->delete();
        return back()->with('success', 'Expense deleted.');
    }

    public function report(Project $project)
    {
        $expenses = $project->expenses()->latest('date')->get();
        return view('expenses.report', compact('project', 'expenses'));
    }

    public function export(Project $project)
    {
        return back()->with('error', 'Export coming soon.');
    }
}
