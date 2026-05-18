@extends('layouts.app')

@section('title', 'Bill of Quantities')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Bill of Quantities</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        {{-- Import --}}
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload me-1"></i>Import Excel
        </button>
        <a href="{{ route('projects.boq.create', $project) }}" class="btn btn-sm text-white" style="background:var(--accent-blue);">
            <i class="bi bi-plus-lg me-1"></i>Add Item
        </a>
    </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold h5 mb-0">{{ $items->total() }}</div>
            <div class="small text-muted">Total Items</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold h5 mb-0" style="color:var(--accent-blue);">
                ₹{{ number_format($project->total_boq_amount / 100000, 2) }}L
            </div>
            <div class="small text-muted">Total BOQ Value</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold h5 mb-0" style="color:var(--accent-green);">
                ₹{{ number_format($project->earned_value / 100000, 2) }}L
            </div>
            <div class="small text-muted">Earned Value</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fw-bold h5 mb-0" style="color:var(--accent-amber);">
                {{ number_format($project->completion_pct, 1) }}%
            </div>
            <div class="small text-muted">Completion</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:3rem;">#</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Rate (₹)</th>
                    <th class="text-end">Amount (₹)</th>
                    <th class="text-end">Done</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="text-muted small">{{ $loop->iteration }}</td>
                    <td><span class="badge bg-light text-secondary border">{{ $item->category }}</span></td>
                    <td class="small" style="max-width:300px;">{{ $item->description }}</td>
                    <td class="small text-muted">{{ $item->unit }}</td>
                    <td class="text-end small">{{ number_format($item->quantity, 3) }}</td>
                    <td class="text-end small">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-end small fw-semibold">{{ number_format($item->amount, 0) }}</td>
                    <td class="text-end small">{{ number_format($item->completion_pct, 1) }}%</td>
                    <td>
                        @php $s = $item->status; @endphp
                        @if($s === 'completed')
                            <span class="badge bg-success">Done</span>
                        @elseif($s === 'in_progress')
                            <span class="badge bg-warning text-dark">In Progress</span>
                        @else
                            <span class="badge bg-secondary">Not Started</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('projects.boq.edit', [$project, $item]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('projects.boq.destroy', [$project, $item]) }}"
                                  onsubmit="return confirm('Delete this BOQ item?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">No BOQ items. Add items or import from Excel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $items->links() }}</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold">Import BOQ from Excel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('projects.boq.import', $project) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="small text-muted">Upload an Excel file with columns: <strong>category, description, unit, quantity, rate</strong></p>
                    <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-blue);">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
