@extends('layouts.app')
@section('title', 'Edit DSR')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.dsr.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0">DSR: {{ Str::limit($boq->description, 60) }}</h4>
        <p class="text-muted small mb-0">{{ $boq->category }} · {{ $boq->unit }}</p>
    </div>
</div>

<form method="POST" action="{{ route('projects.dsr.update', [$project, $boq]) }}">
    @csrf @method('PUT')
    <div class="row g-3">

        {{-- Material --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                    Material
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addRow('material')">
                        <i class="bi bi-plus"></i> Add
                    </button>
                </div>
                <div class="card-body p-3" id="material-rows">
                    @foreach(($dsr->material_json ?? []) as $i => $row)
                    <div class="row g-2 mb-2 dsr-row">
                        <div class="col-5"><input type="text" name="material[{{ $i }}][name]" class="form-control form-control-sm" placeholder="Material name" value="{{ $row['name'] ?? '' }}"></div>
                        <div class="col-2"><input type="number" name="material[{{ $i }}][qty]" class="form-control form-control-sm" placeholder="Qty" step="0.001" value="{{ $row['qty'] ?? '' }}"></div>
                        <div class="col-2"><input type="number" name="material[{{ $i }}][rate]" class="form-control form-control-sm" placeholder="Rate" step="0.01" value="{{ $row['rate'] ?? '' }}"></div>
                        <div class="col-2"><input type="text" name="material[{{ $i }}][unit]" class="form-control form-control-sm" placeholder="Unit" value="{{ $row['unit'] ?? '' }}"></div>
                        <div class="col-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dsr-row').remove()"><i class="bi bi-x"></i></button></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Labour --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                    Labour
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addRow('labour')">
                        <i class="bi bi-plus"></i> Add
                    </button>
                </div>
                <div class="card-body p-3" id="labour-rows">
                    @foreach(($dsr->labour_json ?? []) as $i => $row)
                    <div class="row g-2 mb-2 dsr-row">
                        <div class="col-5"><input type="text" name="labour[{{ $i }}][name]" class="form-control form-control-sm" placeholder="Labour type" value="{{ $row['name'] ?? '' }}"></div>
                        <div class="col-2"><input type="number" name="labour[{{ $i }}][qty]" class="form-control form-control-sm" placeholder="No." step="0.01" value="{{ $row['qty'] ?? '' }}"></div>
                        <div class="col-2"><input type="number" name="labour[{{ $i }}][rate]" class="form-control form-control-sm" placeholder="Rate" step="0.01" value="{{ $row['rate'] ?? '' }}"></div>
                        <div class="col-2"><input type="text" name="labour[{{ $i }}][unit]" class="form-control form-control-sm" placeholder="Unit" value="{{ $row['unit'] ?? '' }}"></div>
                        <div class="col-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dsr-row').remove()"><i class="bi bi-x"></i></button></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Machinery --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                    Machinery
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addRow('machinery')">
                        <i class="bi bi-plus"></i> Add
                    </button>
                </div>
                <div class="card-body p-3" id="machinery-rows">
                    @foreach(($dsr->machinery_json ?? []) as $i => $row)
                    <div class="row g-2 mb-2 dsr-row">
                        <div class="col-5"><input type="text" name="machinery[{{ $i }}][name]" class="form-control form-control-sm" placeholder="Equipment" value="{{ $row['name'] ?? '' }}"></div>
                        <div class="col-2"><input type="number" name="machinery[{{ $i }}][qty]" class="form-control form-control-sm" placeholder="Hrs" step="0.01" value="{{ $row['qty'] ?? '' }}"></div>
                        <div class="col-2"><input type="number" name="machinery[{{ $i }}][rate]" class="form-control form-control-sm" placeholder="Rate/hr" step="0.01" value="{{ $row['rate'] ?? '' }}"></div>
                        <div class="col-2"><input type="text" name="machinery[{{ $i }}][unit]" class="form-control form-control-sm" placeholder="Unit" value="{{ $row['unit'] ?? '' }}"></div>
                        <div class="col-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dsr-row').remove()"><i class="bi bi-x"></i></button></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-blue);">
            <i class="bi bi-check2 me-1"></i>Save DSR
        </button>
        <a href="{{ route('projects.dsr.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
const counters = {material: {{ count($dsr->material_json ?? []) }}, labour: {{ count($dsr->labour_json ?? []) }}, machinery: {{ count($dsr->machinery_json ?? []) }}};
function addRow(type) {
    const i = counters[type]++;
    const tpl = `<div class="row g-2 mb-2 dsr-row">
        <div class="col-5"><input type="text" name="${type}[${i}][name]" class="form-control form-control-sm" placeholder="Name"></div>
        <div class="col-2"><input type="number" name="${type}[${i}][qty]" class="form-control form-control-sm" placeholder="Qty" step="0.001"></div>
        <div class="col-2"><input type="number" name="${type}[${i}][rate]" class="form-control form-control-sm" placeholder="Rate" step="0.01"></div>
        <div class="col-2"><input type="text" name="${type}[${i}][unit]" class="form-control form-control-sm" placeholder="Unit"></div>
        <div class="col-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.dsr-row').remove()"><i class="bi bi-x"></i></button></div>
    </div>`;
    document.getElementById(type + '-rows').insertAdjacentHTML('beforeend', tpl);
}
</script>
@endpush
