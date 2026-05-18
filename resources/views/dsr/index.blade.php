@extends('layouts.app')
@section('title', 'DSR Calculator')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">DSR Calculator</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <a href="{{ route('projects.dsr.report', $project) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-file-text me-1"></i>Report
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
                    <th class="text-end">BOQ Rate (₹)</th>
                    <th class="text-end">DSR Cost (₹)</th>
                    <th>DSR Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($boqItems as $item)
                <tr>
                    <td><span class="badge bg-light text-secondary border">{{ $item->category }}</span></td>
                    <td class="small">{{ $item->description }}</td>
                    <td class="small text-muted">{{ $item->unit }}</td>
                    <td class="text-end small">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-end small">
                        @if($item->dsrItem)
                            {{ number_format($item->dsrItem->total_cost, 2) }}
                        @else — @endif
                    </td>
                    <td>
                        @if($item->dsrItem)
                            <span class="badge bg-success">Configured</span>
                        @else
                            <span class="badge bg-secondary">Not Set</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('projects.dsr.edit', [$project, $item]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No BOQ items. Add BOQ items first.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
