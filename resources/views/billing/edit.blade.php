@extends('layouts.app')
@section('title', 'Edit Bill')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.billing.show', [$project, $bill]) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Edit Bill #{{ $bill->bill_number }}</h4>
</div>
<div class="row"><div class="col-lg-7">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.billing.update', [$project, $bill]) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bill Number</label>
                        <input type="text" name="bill_number" class="form-control form-control-sm"
                               value="{{ old('bill_number', $bill->bill_number) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bill Date</label>
                        <input type="date" name="bill_date" class="form-control form-control-sm"
                               value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Gross Amount (₹)</label>
                        <input type="number" name="gross_amount" step="0.01" class="form-control form-control-sm"
                               value="{{ old('gross_amount', $bill->gross_amount) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes', $bill->notes) }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-amber);">Save Changes</button>
                    <a href="{{ route('projects.billing.show', [$project, $bill]) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
