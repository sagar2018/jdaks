@extends('layouts.app')
@section('title', 'Progress Report')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Progress Report</h4>
        <p class="text-muted small mb-0">{{ $project->name }}</p>
    </div>
    <a href="{{ route('projects.progress.index', $project) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th class="text-end">Total Qty</th>
                    <th class="text-end">Done Qty</th>
                    <th class="text-end">%</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($boqItems as $item)
                <tr>
                    <td><span class="badge bg-light text-secondary border">{{ $item->category }}</span></td>
                    <td class="small">{{ $item->description }}</td>
                    <td class="small text-muted">{{ $item->unit }}</td>
                    <td class="text-end small">{{ number_format($item->quantity, 3) }}</td>
                    <td class="text-end small">{{ number_format($item->done_quantity, 3) }}</td>
                    <td class="text-end small fw-semibold">{{ number_format($item->completion_pct, 1) }}%</td>
                    <td>
                        @if($item->status === 'completed') <span class="badge bg-success">Done</span>
                        @elseif($item->status === 'in_progress') <span class="badge bg-warning text-dark">In Progress</span>
                        @else <span class="badge bg-secondary">Not Started</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No BOQ items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
