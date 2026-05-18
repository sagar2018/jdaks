@extends('layouts.app')
@section('title', 'RA Bill ' . $bill->bill_number)
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.billing.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Bill #{{ $bill->bill_number }}</h4>
            <p class="text-muted small mb-0">{{ $bill->bill_date->format('d M Y') }}</p>
        </div>
    </div>
    @php $colors = ['draft'=>'secondary','submitted'=>'primary','certified'=>'info','approved'=>'warning','paid'=>'success']; @endphp
    <span class="badge bg-{{ $colors[$bill->status] ?? 'secondary' }} fs-6">{{ ucfirst($bill->status) }}</span>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted small">Gross Amount</td><td class="text-end fw-bold">₹{{ number_format($bill->gross_amount, 2) }}</td></tr>
                    @if($bill->deductions_json)
                        @foreach($bill->deductions_json as $ded)
                        <tr><td class="text-muted small ps-3">{{ $ded['label'] ?? 'Deduction' }}</td><td class="text-end text-danger small">- ₹{{ number_format($ded['amount'] ?? 0, 2) }}</td></tr>
                        @endforeach
                    @endif
                    <tr class="fw-bold"><td>Net Payable</td><td class="text-end" style="color:var(--accent-green);">₹{{ number_format($bill->net_payable, 2) }}</td></tr>
                    @if($bill->paid_amount)<tr><td class="text-muted small">Paid Amount</td><td class="text-end small">₹{{ number_format($bill->paid_amount, 2) }}</td></tr>@endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Bill Timeline</h6>
                @foreach(['draft' => null, 'submitted' => $bill->submitted_at, 'certified' => $bill->certified_at, 'approved' => $bill->approved_at, 'paid' => $bill->paid_at] as $stage => $ts)
                <div class="d-flex align-items-center mb-2">
                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center"
                         style="width:24px;height:24px;background:{{ $ts || $stage === 'draft' ? 'var(--accent-green)' : '#dee2e6' }};">
                        <i class="bi bi-check text-white" style="font-size:.65rem;"></i>
                    </div>
                    <span class="small {{ $ts || $stage === 'draft' ? 'fw-semibold' : 'text-muted' }}">{{ ucfirst($stage) }}</span>
                    @if($ts) <span class="ms-auto small text-muted">{{ $ts->format('d M Y') }}</span> @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if($bill->notes)
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body"><p class="small mb-0">{{ $bill->notes }}</p></div>
</div>
@endif

<div class="d-flex gap-2 mt-3">
    @if($bill->next_status)
    <form method="POST" action="{{ route('projects.billing.advance', [$project, $bill]) }}">
        @csrf
        <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-blue);">
            Advance to {{ ucfirst($bill->next_status) }}
        </button>
    </form>
    @endif
    <a href="{{ route('projects.billing.pdf', [$project, $bill]) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-file-earmark-pdf me-1"></i>Download PDF
    </a>
</div>
@endsection
