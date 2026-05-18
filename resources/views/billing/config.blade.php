@extends('layouts.app')
@section('title', 'Billing Configuration')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.billing.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Billing Configuration</h4>
</div>
<div class="row"><div class="col-lg-7">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.billing.config.save', $project) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">LOA Number</label>
                        <input type="text" name="loa_number" class="form-control form-control-sm"
                               value="{{ old('loa_number', $config->loa_number ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tendered Value (₹)</label>
                        <input type="number" name="tendered_value" step="0.01" class="form-control form-control-sm"
                               value="{{ old('tendered_value', $config->tendered_value ?? '') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bid Type</label>
                        <select name="bid_type" class="form-select form-select-sm">
                            <option value="">— None —</option>
                            <option value="below" @selected(old('bid_type', $config->bid_type ?? '') === 'below')>Below Estimate</option>
                            <option value="at_par" @selected(old('bid_type', $config->bid_type ?? '') === 'at_par')>At Par</option>
                            <option value="above" @selected(old('bid_type', $config->bid_type ?? '') === 'above')>Above Estimate</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Bid % (±)</label>
                        <input type="number" name="bid_pct" step="0.01" class="form-control form-control-sm"
                               value="{{ old('bid_pct', $config->bid_pct ?? '') }}">
                    </div>
                </div>
                <hr>
                <h6 class="fw-semibold mb-3">Deduction Percentages</h6>
                <div class="row g-3">
                    @foreach(['sd_pct' => 'Security Deposit %', 'it_tds_pct' => 'IT TDS %', 'labour_cess_pct' => 'Labour Cess %', 'gst_tds_pct' => 'GST TDS %'] as $field => $label)
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">{{ $label }}</label>
                        <input type="number" name="{{ $field }}" step="0.01" class="form-control form-control-sm"
                               value="{{ old($field, $config->$field ?? '') }}">
                    </div>
                    @endforeach
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-amber);">
                        <i class="bi bi-check2 me-1"></i>Save Configuration
                    </button>
                    <a href="{{ route('projects.billing.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
