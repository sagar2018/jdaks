@extends('layouts.app')
@section('title', 'Inventory Report')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Inventory Report — {{ $project->name }}</h4>
    <a href="{{ route('projects.inventory.index', $project) }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>
@foreach($materials as $mat)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-transparent d-flex justify-content-between">
        <span class="fw-semibold">{{ $mat->name }}</span>
        <span class="small text-muted">Current Stock: <strong>{{ number_format($mat->current_stock, 3) }} {{ $mat->unit }}</strong></span>
    </div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Type</th><th class="text-end">Qty</th><th class="text-end">Rate</th><th>Notes</th></tr></thead>
            <tbody>
                @forelse($mat->transactions as $txn)
                <tr>
                    <td class="small">{{ $txn->date->format('d M Y') }}</td>
                    <td><span class="badge {{ $txn->txn_type === 'purchase' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($txn->txn_type) }}</span></td>
                    <td class="text-end small">{{ number_format($txn->quantity, 3) }}</td>
                    <td class="text-end small">{{ $txn->rate ? '₹' . number_format($txn->rate, 2) : '—' }}</td>
                    <td class="small text-muted">{{ $txn->notes }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted small text-center py-2">No transactions.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection
