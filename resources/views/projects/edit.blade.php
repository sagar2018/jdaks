@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Edit: {{ $project->name }}</h4>
</div>

<div class="row">
    <div class="col-lg-9">
        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf @method('PUT')

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Basic Information</div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   value="{{ old('name', $project->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Package No.</label>
                            <input type="text" name="package" class="form-control form-control-sm"
                                   value="{{ old('package', $project->package) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Location</label>
                            <input type="text" name="location" class="form-control form-control-sm"
                                   value="{{ old('location', $project->location) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contractor</label>
                            <input type="text" name="contractor" class="form-control form-control-sm"
                                   value="{{ old('contractor', $project->contractor) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Chainage & Dates</div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Chainage Start (km)</label>
                            <input type="number" name="chainage_start" step="0.001" class="form-control form-control-sm"
                                   value="{{ old('chainage_start', $project->chainage_start) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Chainage End (km)</label>
                            <input type="number" name="chainage_end" step="0.001" class="form-control form-control-sm"
                                   value="{{ old('chainage_end', $project->chainage_end) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm"
                                   value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm"
                                   value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Deadline Date</label>
                            <input type="date" name="deadline_date" class="form-control form-control-sm"
                                   value="{{ old('deadline_date', $project->deadline_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Bid Date</label>
                            <input type="date" name="bid_date" class="form-control form-control-sm"
                                   value="{{ old('bid_date', $project->bid_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Contract Details</div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Bid Type</label>
                            <select name="bid_type" class="form-select form-select-sm">
                                <option value="">— Select —</option>
                                <option value="percentage" @selected(old('bid_type', $project->bid_type) === 'percentage')>Percentage</option>
                                <option value="lump_sum"   @selected(old('bid_type', $project->bid_type) === 'lump_sum')>Lump Sum</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Bid %</label>
                            <input type="number" name="bid_pct" step="0.01" class="form-control form-control-sm"
                                   value="{{ old('bid_pct', $project->bid_pct) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Bitumen Grade</label>
                            <input type="text" name="bit_grade" class="form-control form-control-sm"
                                   value="{{ old('bit_grade', $project->bit_grade) }}">
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="dual_lane" id="dual_lane"
                                       value="1" @checked(old('dual_lane', $project->dual_lane))>
                                <label class="form-check-label small fw-semibold" for="dual_lane">Dual Lane</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Notes</div>
                <div class="card-body p-4">
                    <textarea name="notes" class="form-control form-control-sm" rows="3">{{ old('notes', $project->notes) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-blue);">
                    <i class="bi bi-check2 me-1"></i>Save Changes
                </button>
                <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                @can('delete projects')
                <form method="POST" action="{{ route('projects.destroy', $project) }}" class="ms-auto"
                      onsubmit="return confirm('Permanently delete this project and all its data?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i>Delete Project
                    </button>
                </form>
                @endcan
            </div>
        </form>
    </div>
</div>
@endsection
