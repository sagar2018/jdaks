<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Month (YYYY-MM) <span class="text-danger">*</span></label>
        <input type="month" name="month" class="form-control form-control-sm @error('month') is-invalid @enderror"
               value="{{ old('month', $index->month ?? '') }}" required>
        @error('month')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Base Month</label>
        <input type="month" name="base_month" class="form-control form-control-sm"
               value="{{ old('base_month', $index->base_month ?? '') }}">
    </div>
    @foreach(['labour_idx' => 'Labour', 'cement_idx' => 'Cement', 'steel_idx' => 'Steel', 'bitumen_idx' => 'Bitumen', 'pol_idx' => 'POL', 'other_idx' => 'Other', 'plant_idx' => 'Plant & Machinery'] as $field => $label)
    <div class="col-md-3">
        <label class="form-label small fw-semibold">{{ $label }}</label>
        <input type="number" name="{{ $field }}" step="0.0001" class="form-control form-control-sm"
               value="{{ old($field, $index->$field ?? '') }}">
    </div>
    @endforeach
</div>
