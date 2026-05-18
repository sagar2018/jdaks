@extends('layouts.app')
@section('title', 'Record Transaction')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.inventory.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Record Inventory Transaction</h4>
</div>
<div class="row"><div class="col-lg-6">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.inventory.transactions.store', $project) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Material <span class="text-danger">*</span></label>
                        <select name="material_id" class="form-select form-select-sm @error('material_id') is-invalid @enderror" required>
                            <option value="">Select…</option>
                            @foreach($materials as $mat)
                                <option value="{{ $mat->id }}" @selected(old('material_id') == $mat->id)>
                                    {{ $mat->name }} ({{ $mat->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('material_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Transaction Type <span class="text-danger">*</span></label>
                        <select name="txn_type" class="form-select form-select-sm" required>
                            <option value="purchase" @selected(old('txn_type','purchase') === 'purchase')>Purchase (IN)</option>
                            <option value="consumption" @selected(old('txn_type') === 'consumption')>Consumption (OUT)</option>
                            <option value="wastage" @selected(old('txn_type') === 'wastage')>Wastage (OUT)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" step="0.001" class="form-control form-control-sm" value="{{ old('quantity') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Rate (₹)</label>
                        <input type="number" name="rate" step="0.01" class="form-control form-control-sm" value="{{ old('rate') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-green);">Record Transaction</button>
                    <a href="{{ route('projects.inventory.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
