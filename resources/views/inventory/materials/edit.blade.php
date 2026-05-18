@extends('layouts.app')
@section('title', 'Edit Material')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.inventory.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Edit Material: {{ $mat->name }}</h4>
</div>
<div class="row"><div class="col-lg-6">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.inventory.materials.update', [$project, $mat]) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Material Code</label>
                        <input type="text" name="material_code" class="form-control form-control-sm" value="{{ old('material_code', $mat->material_code) }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $mat->name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Unit <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control form-control-sm" value="{{ old('unit', $mat->unit) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Opening Stock</label>
                        <input type="number" name="opening_stock" step="0.001" class="form-control form-control-sm" value="{{ old('opening_stock', $mat->opening_stock) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Reorder Qty</label>
                        <input type="number" name="reorder_qty" step="0.001" class="form-control form-control-sm" value="{{ old('reorder_qty', $mat->reorder_qty) }}">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-green);">Save Changes</button>
                    <a href="{{ route('projects.inventory.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
