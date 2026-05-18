@extends('layouts.app')
@section('title', 'RA Billing')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">RA Billing</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.billing.config', $project) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-gear me-1"></i>Configure
        </a>
        <a href="{{ route('projects.billing.create', $project) }}" class="btn btn-sm text-white" style="background:var(--accent-amber);">
            <i class="bi bi-plus-lg me-1"></i>New Bill
        </a>
    </div>
</div>

@if(!$config)
<div class="alert alert-warning py-2 small">
    <i class="bi bi-exclamation-triangle me-1"></i>
    Billing not configured. <a href="{{ route('projects.billing.config', $project) }}">Set up billing configuration</a> first.
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Bill #</th>
                    <th>Date</th>
                    <th class="text-end">Gross (₹)</th>
                    <th class="text-end">Net Payable (₹)</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bills as $bill)
                <tr>
                    <td class="fw-semibold small">{{ $bill->bill_number }}</td>
                    <td class="small">{{ $bill->bill_date->format('d M Y') }}</td>
                    <td class="text-end small">{{ number_format($bill->gross_amount, 2) }}</td>
                    <td class="text-end small fw-semibold">{{ number_format($bill->net_payable, 2) }}</td>
                    <td>
                        @php $colors = ['draft'=>'secondary','submitted'=>'primary','certified'=>'info','approved'=>'warning','paid'=>'success']; @endphp
                        <span class="badge bg-{{ $colors[$bill->status] ?? 'secondary' }}">{{ ucfirst($bill->status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('projects.billing.show', [$project, $bill]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($bill->status === 'draft')
                            <a href="{{ route('projects.billing.edit', [$project, $bill]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif
                            @if($bill->next_status)
                            <form method="POST" action="{{ route('projects.billing.advance', [$project, $bill]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary" title="Advance to {{ ucfirst($bill->next_status) }}">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('projects.billing.pdf', [$project, $bill]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No RA bills yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
