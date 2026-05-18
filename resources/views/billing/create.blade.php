@extends('layouts.app')
@section('title', 'New RA Bill')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.billing.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">New RA Bill</h4>
</div>
<div class="row"><div class="col-lg-8">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.billing.store', $project) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bill Number <span class="text-danger">*</span></label>
                        <input type="text" name="bill_number" class="form-control form-control-sm @error('bill_number') is-invalid @enderror"
                               value="{{ old('bill_number') }}" required>
                        @error('bill_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bill Date <span class="text-danger">*</span></label>
                        <input type="date" name="bill_date" class="form-control form-control-sm @error('bill_date') is-invalid @enderror"
                               value="{{ old('bill_date', date('Y-m-d')) }}" required>
                        @error('bill_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Gross Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="gross_amount" step="0.01" class="form-control form-control-sm @error('gross_amount') is-invalid @enderror"
                               value="{{ old('gross_amount') }}" required>
                        @error('gross_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-amber);">
                        <i class="bi bi-check2 me-1"></i>Create Bill
                    </button>
                    <a href="{{ route('projects.billing.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
