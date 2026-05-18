@extends('layouts.app')
@section('title', 'Add Progress Entry')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.progress.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Add Progress Entry</h4>
</div>
<div class="row"><div class="col-lg-7">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.progress.store', $project) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">BOQ Item <span class="text-danger">*</span></label>
                        <select name="boq_item_id" class="form-select form-select-sm @error('boq_item_id') is-invalid @enderror" required>
                            <option value="">Select item…</option>
                            @foreach($boqItems->groupBy('category') as $cat => $items)
                                <optgroup label="{{ $cat }}">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" @selected(old('boq_item_id') == $item->id)>
                                            {{ $item->description }} ({{ $item->unit }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('boq_item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control form-control-sm @error('date') is-invalid @enderror"
                               value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" step="0.001" class="form-control form-control-sm @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity') }}" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Chainage From</label>
                        <input type="number" name="chainage_from" step="0.001" class="form-control form-control-sm"
                               value="{{ old('chainage_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Chainage To</label>
                        <input type="number" name="chainage_to" step="0.001" class="form-control form-control-sm"
                               value="{{ old('chainage_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="completed" @selected(old('status','completed') === 'completed')>Completed</option>
                            <option value="partial" @selected(old('status') === 'partial')>Partial</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-green);">
                        <i class="bi bi-check2 me-1"></i>Save Entry
                    </button>
                    <a href="{{ route('projects.progress.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
