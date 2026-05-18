<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Date <span class="text-danger">*</span></label>
        <input type="date" name="date" class="form-control form-control-sm @error('date') is-invalid @enderror"
               value="{{ old('date', isset($expense) ? $expense->date->format('Y-m-d') : date('Y-m-d')) }}" required>
        @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
        <select name="category" class="form-select form-select-sm @error('category') is-invalid @enderror" required>
            <option value="labour" @selected(old('category', $expense->category ?? '') === 'labour')>Labour</option>
            <option value="machinery" @selected(old('category', $expense->category ?? '') === 'machinery')>Machinery</option>
            <option value="other" @selected(old('category', $expense->category ?? '') === 'other')>Other</option>
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label small fw-semibold">Amount (₹) <span class="text-danger">*</span></label>
        <input type="number" name="amount" step="0.01" class="form-control form-control-sm @error('amount') is-invalid @enderror"
               value="{{ old('amount', $expense->amount ?? '') }}" required>
        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label small fw-semibold">Description <span class="text-danger">*</span></label>
        <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror" rows="2" required>{{ old('description', $expense->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
