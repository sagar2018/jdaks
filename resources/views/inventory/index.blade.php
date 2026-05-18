@extends('layouts.app')
@section('title', 'Inventory')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Inventory</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.inventory.transactions.create', $project) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left-right me-1"></i>Record Transaction
        </a>
        <a href="{{ route('projects.inventory.materials.create', $project) }}" class="btn btn-sm text-white" style="background:var(--accent-green);">
            <i class="bi bi-plus-lg me-1"></i>Add Material
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Material</th>
                    <th>Unit</th>
                    <th class="text-end">Opening</th>
                    <th class="text-end">Current Stock</th>
                    <th class="text-end">Reorder Qty</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $mat)
                @php $status = $mat->stock_status; @endphp
                <tr>
                    <td class="small text-muted">{{ $mat->material_code ?? '—' }}</td>
                    <td class="fw-semibold small">{{ $mat->name }}</td>
                    <td class="small text-muted">{{ $mat->unit }}</td>
                    <td class="text-end small">{{ number_format($mat->opening_stock ?? 0, 3) }}</td>
                    <td class="text-end small fw-semibold">{{ number_format($mat->current_stock, 3) }}</td>
                    <td class="text-end small">{{ $mat->reorder_qty ? number_format($mat->reorder_qty, 3) : '—' }}</td>
                    <td>
                        @if($status === 'out') <span class="badge bg-danger">Out</span>
                        @elseif($status === 'low') <span class="badge bg-warning text-dark">Low</span>
                        @else <span class="badge bg-success">OK</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('projects.inventory.materials.edit', [$project, $mat]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('projects.inventory.materials.destroy', [$project, $mat]) }}" class="d-inline"
                              onsubmit="return confirm('Delete this material?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No materials added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
