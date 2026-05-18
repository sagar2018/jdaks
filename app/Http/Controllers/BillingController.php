<?php

namespace App\Http\Controllers;

use App\Models\BillingConfig;
use App\Models\Project;
use App\Models\RaBill;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(Project $project)
    {
        $bills  = $project->raBills()->latest('bill_date')->get();
        $config = $project->billingConfig;
        return view('billing.index', compact('project', 'bills', 'config'));
    }

    public function config(Project $project)
    {
        $config = $project->billingConfig ?? new BillingConfig(['project_id' => $project->id]);
        return view('billing.config', compact('project', 'config'));
    }

    public function saveConfig(Request $request, Project $project)
    {
        $data = $request->validate([
            'loa_number'      => 'nullable|string|max:100',
            'tendered_value'  => 'nullable|numeric|min:0',
            'bid_type'        => 'nullable|in:below,at_par,above',
            'bid_pct'         => 'nullable|numeric',
            'sd_pct'          => 'nullable|numeric|min:0|max:100',
            'it_tds_pct'      => 'nullable|numeric|min:0|max:100',
            'labour_cess_pct' => 'nullable|numeric|min:0|max:100',
            'gst_tds_pct'     => 'nullable|numeric|min:0|max:100',
        ]);

        $data['configured_at'] = now();
        $project->billingConfig()->updateOrCreate(['project_id' => $project->id], $data);

        return redirect()->route('projects.billing.index', $project)
            ->with('success', 'Billing configuration saved.');
    }

    public function create(Project $project)
    {
        $config   = $project->billingConfig;
        $boqItems = $project->boqItems()->with('progressEntries')->orderBy('category')->get();
        $lastBill = $project->raBills()->latest('bill_date')->first();
        return view('billing.create', compact('project', 'config', 'boqItems', 'lastBill'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'bill_number'       => 'required|string|max:50',
            'bill_date'         => 'required|date',
            'gross_amount'      => 'required|numeric|min:0',
            'deductions_json'   => 'nullable|array',
            'notes'             => 'nullable|string',
        ]);

        $deductions = $request->input('deductions_json', []);
        $net = $data['gross_amount'] - array_sum(array_column($deductions, 'amount'));

        RaBill::create([
            'project_id'      => $project->id,
            'bill_number'     => $data['bill_number'],
            'bill_date'       => $data['bill_date'],
            'gross_amount'    => $data['gross_amount'],
            'deductions_json' => $deductions,
            'net_payable'     => max(0, $net),
            'status'          => 'draft',
            'notes'           => $data['notes'] ?? null,
            'created_by'      => auth()->id(),
        ]);

        return redirect()->route('projects.billing.index', $project)
            ->with('success', 'RA Bill created.');
    }

    public function show(Project $project, RaBill $bill)
    {
        return view('billing.show', compact('project', 'bill'));
    }

    public function edit(Project $project, RaBill $bill)
    {
        $config = $project->billingConfig;
        return view('billing.edit', compact('project', 'bill', 'config'));
    }

    public function update(Request $request, Project $project, RaBill $bill)
    {
        $data = $request->validate([
            'bill_number'  => 'required|string|max:50',
            'bill_date'    => 'required|date',
            'gross_amount' => 'required|numeric|min:0',
            'notes'        => 'nullable|string',
        ]);
        $bill->update($data);
        return redirect()->route('projects.billing.show', [$project, $bill])->with('success', 'Bill updated.');
    }

    public function destroy(Project $project, RaBill $bill)
    {
        abort_unless($bill->status === 'draft', 403, 'Only draft bills can be deleted.');
        $bill->delete();
        return redirect()->route('projects.billing.index', $project)->with('success', 'Bill deleted.');
    }

    public function advance(Request $request, Project $project, RaBill $bill)
    {
        $next = $bill->next_status;
        if (!$next) return back()->with('error', 'Bill is already in final status.');

        $timestamps = [
            'submitted' => 'submitted_at',
            'certified' => 'certified_at',
            'approved'  => 'approved_at',
            'paid'      => 'paid_at',
        ];

        $bill->update(array_filter([
            'status'                          => $next,
            $timestamps[$next] ?? null        => now(),
            'paid_amount' => $next === 'paid' ? $bill->net_payable : null,
        ]));

        return back()->with('success', "Bill status updated to " . ucfirst($next) . ".");
    }

    public function pdf(Project $project, RaBill $bill)
    {
        return back()->with('error', 'PDF export coming soon.');
    }
}
