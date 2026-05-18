@extends('layouts.app')
@section('title', 'DSR Report')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">DSR Report — {{ $project->name }}</h4>
    <a href="{{ route('projects.dsr.index', $project) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th>Unit</th>
                    <th class="text-end">BOQ Rate</th>
                    <th class="text-end">DSR Cost</th>
                    <th class="text-end">Diff</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                @php $dsrCost = $item->dsrItem?->total_cost ?? 0; $diff = $item->rate - $dsrCost; @endphp
                <tr>
                    <td class="small">{{ $item->description }}</td>
                    <td class="small text-muted">{{ $item->unit }}</td>
                    <td class="text-end small">{{ number_format($item->rate, 2) }}</td>
                    <td class="text-end small">{{ $item->dsrItem ? number_format($dsrCost, 2) : '—' }}</td>
                    <td class="text-end small fw-semibold {{ $diff >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $item->dsrItem ? (($diff >= 0 ? '+' : '') . number_format($diff, 2)) : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
