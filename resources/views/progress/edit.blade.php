@extends('layouts.app')
@section('title', 'Edit Progress Entry')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.progress.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Edit Progress Entry</h4>
</div>
<div class="row"><div class="col-lg-7">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.progress.update', [$project, $entry]) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">BOQ Item <span class="text-danger">*</span></label>
                        <select name="boq_item_id" class="form-select form-select-sm" required>
                            @foreach($boqItems->groupBy('category') as $cat => $items)
                                <optgroup label="{{ $cat }}">
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" @selected(old('boq_item_id', $entry->boq_item_id) == $item->id)>
                                            {{ $item->description }} ({{ $item->unit }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control form-control-sm"
                               value="{{ old('date', $entry->date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" step="0.001" class="form-control form-control-sm"
                               value="{{ old('quantity', $entry->quantity) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Chainage From</label>
                        <input type="number" name="chainage_from" step="0.001" class="form-control form-control-sm"
                               value="{{ old('chainage_from', $entry->chainage_from) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Chainage To</label>
                        <input type="number" name="chainage_to" step="0.001" class="form-control form-control-sm"
                               value="{{ old('chainage_to', $entry->chainage_to) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="completed" @selected(old('status', $entry->status) === 'completed')>Completed</option>
                            <option value="partial" @selected(old('status', $entry->status) === 'partial')>Partial</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ old('notes', $entry->notes) }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-green);">
                        <i class="bi bi-check2 me-1"></i>Save Changes
                    </button>
                    <a href="{{ route('projects.progress.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
