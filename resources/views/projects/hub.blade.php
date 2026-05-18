@extends('layouts.app')

@section('title', $project->name . ' — Hub')

@section('content')
{{-- Project Header --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div class="d-flex align-items-start">
        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary me-3 mt-1" title="All Projects">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-1">{{ $project->name }}</h4>
            <p class="text-muted small mb-0">
            @if($project->package)<span class="me-3"><i class="bi bi-hash me-1"></i>{{ $project->package }}</span>@endif
            @if($project->location)<span class="me-3"><i class="bi bi-geo-alt me-1"></i>{{ $project->location }}</span>@endif
            @if($project->contractor)<span><i class="bi bi-building me-1"></i>{{ $project->contractor }}</span>@endif
        </p>
        </div>
    </div>
    @can('edit projects')
    <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-pencil me-1"></i>Edit Project
    </a>
    @endcan
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="h5 fw-bold mb-0" style="color:var(--accent-blue);">
                ₹{{ number_format($stats['total_boq'] / 100000, 2) }}L
            </div>
            <div class="small text-muted">Total BOQ</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="h5 fw-bold mb-0" style="color:var(--accent-green);">
                ₹{{ number_format($stats['earned_value'] / 100000, 2) }}L
            </div>
            <div class="small text-muted">Earned Value</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="h5 fw-bold mb-0" style="color:var(--accent-amber);">
                {{ number_format($stats['completion'], 1) }}%
            </div>
            <div class="small text-muted">Completion</div>
            <div class="progress mx-3 mt-1" style="height:4px;">
                <div class="progress-bar" style="width:{{ min($stats['completion'], 100) }}%;background:var(--accent-amber);"></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="h5 fw-bold mb-0">{{ $stats['ra_bills'] }}</div>
            <div class="small text-muted">RA Bills
                @if($stats['pending_bills'] > 0)
                    <span class="badge bg-warning text-dark">{{ $stats['pending_bills'] }} pending</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Module Grid --}}
<h6 class="fw-semibold text-muted mb-3 text-uppercase" style="letter-spacing:.05em;font-size:.7rem;">Modules</h6>
<div class="row g-3">
    @php
    $modules = [
        ['name' => 'Bill of Quantities', 'icon' => 'bi-list-columns-reverse', 'route' => 'projects.boq.index',      'color' => 'accent-blue',  'desc' => 'Manage BOQ items and quantities'],
        ['name' => 'Progress Tracker',   'icon' => 'bi-graph-up-arrow',       'route' => 'projects.progress.index',  'color' => 'accent-green', 'desc' => 'Record daily progress entries'],
        ['name' => 'RA Billing',         'icon' => 'bi-receipt-cutoff',       'route' => 'projects.billing.index',   'color' => 'accent-amber', 'desc' => 'Running account bills & deductions'],
        ['name' => 'DSR Calculator',     'icon' => 'bi-calculator',           'route' => 'projects.dsr.index',       'color' => 'accent-blue',  'desc' => 'Daily Schedule of Rates costing'],
        ['name' => 'Inventory',          'icon' => 'bi-boxes',                'route' => 'projects.inventory.index', 'color' => 'accent-green', 'desc' => 'Material stock & transactions'],
        ['name' => 'Price Variation',    'icon' => 'bi-currency-rupee',       'route' => 'projects.pv.index',        'color' => 'accent-red',   'desc' => 'Price escalation / de-escalation'],
        ['name' => 'Expenses',           'icon' => 'bi-wallet2',              'route' => 'projects.expenses.index',  'color' => 'accent-amber', 'desc' => 'Labour, machinery & other expenses'],
    ];
    @endphp

    @foreach($modules as $mod)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <a href="{{ route($mod['route'], $project) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 module-card">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="rounded-2 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;background:var(--{{ $mod['color'] }});flex-shrink:0;">
                        <i class="bi {{ $mod['icon'] }} text-white fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">{{ $mod['name'] }}</div>
                        <div class="text-muted" style="font-size:.75rem;">{{ $mod['desc'] }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- Project Details Panel --}}
<div class="row g-3 mt-2">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">Project Details</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    @if($project->chainage_start !== null && $project->chainage_end !== null)
                    <tr><td class="text-muted small">Chainage</td><td class="small">{{ $project->chainage_start }} – {{ $project->chainage_end }} km</td></tr>
                    @endif
                    @if($project->start_date)
                    <tr><td class="text-muted small">Start Date</td><td class="small">{{ $project->start_date->format('d M Y') }}</td></tr>
                    @endif
                    @if($project->deadline_date)
                    <tr><td class="text-muted small">Deadline</td><td class="small">{{ $project->deadline_date->format('d M Y') }}</td></tr>
                    @endif
                    @if($project->bid_type)
                    <tr><td class="text-muted small">Bid Type</td><td class="small">{{ ucfirst(str_replace('_', ' ', $project->bid_type)) }}</td></tr>
                    @endif
                    @if($project->bid_pct)
                    <tr><td class="text-muted small">Bid %</td><td class="small">{{ $project->bid_pct > 0 ? '+' : '' }}{{ $project->bid_pct }}%</td></tr>
                    @endif
                    @if($project->bit_grade)
                    <tr><td class="text-muted small">Bitumen Grade</td><td class="small">{{ $project->bit_grade }}</td></tr>
                    @endif
                    <tr><td class="text-muted small">Dual Lane</td><td class="small">{{ $project->dual_lane ? 'Yes' : 'No' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    @if($project->notes)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold">Notes</div>
            <div class="card-body">
                <p class="small mb-0">{{ $project->notes }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
