@extends('layouts.app')
@section('title', 'Price Variation')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Price Variation Indices</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.pv.calculate', $project) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-calculator me-1"></i>Calculate
        </a>
        <a href="{{ route('projects.pv.create', $project) }}" class="btn btn-sm text-white" style="background:var(--accent-red);">
            <i class="bi bi-plus-lg me-1"></i>Add Month
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Month</th>
                    <th class="text-end">Labour</th>
                    <th class="text-end">Cement</th>
                    <th class="text-end">Steel</th>
                    <th class="text-end">Bitumen</th>
                    <th class="text-end">POL</th>
                    <th class="text-end">Other</th>
                    <th class="text-end">Plant</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($indices as $idx)
                <tr>
                    <td class="fw-semibold small">{{ $idx->month }}</td>
                    <td class="text-end small">{{ $idx->labour_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->cement_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->steel_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->bitumen_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->pol_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->other_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->plant_idx ?? '—' }}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('projects.pv.edit', [$project, $idx]) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('projects.pv.destroy', [$project, $idx]) }}" onsubmit="return confirm('Delete this index?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No indices added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
