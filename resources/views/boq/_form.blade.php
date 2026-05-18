<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
        <input type="text" name="category" list="categories"
               class="form-control form-control-sm @error('category') is-invalid @enderror"
               value="{{ old('category', $item->category ?? '') }}" required>
        <datalist id="categories">
            @foreach($categories ?? [] as $cat)
                <option value="{{ $cat }}">
            @endforeach
        </datalist>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label small fw-semibold">Description <span class="text-danger">*</span></label>
        <textarea name="description" rows="2"
                  class="form-control form-control-sm @error('description') is-invalid @enderror"
                  required>{{ old('description', $item->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label small fw-semibold">Unit <span class="text-danger">*</span></label>
        <input type="text" name="unit" list="units"
               class="form-control form-control-sm @error('unit') is-invalid @enderror"
               value="{{ old('unit', $item->unit ?? '') }}" required>
        <datalist id="units">
            @foreach(['m', 'm2', 'm3', 'MT', 'kg', 'No.', 'LS', 'RMT', 'cum'] as $u)
                <option value="{{ $u }}">
            @endforeach
        </datalist>
        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label small fw-semibold">Quantity <span class="text-danger">*</span></label>
        <input type="number" name="quantity" step="0.001"
               class="form-control form-control-sm @error('quantity') is-invalid @enderror"
               value="{{ old('quantity', $item->quantity ?? '') }}" required>
        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label small fw-semibold">Rate (₹) <span class="text-danger">*</span></label>
        <input type="number" name="rate" step="0.01"
               class="form-control form-control-sm @error('rate') is-invalid @enderror"
               value="{{ old('rate', $item->rate ?? '') }}" required id="rateInput">
        @error('rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label small fw-semibold">Amount (₹)</label>
        <input type="text" id="amountDisplay" class="form-control form-control-sm" readonly
               value="{{ number_format(($item->amount ?? 0), 2) }}">
    </div>

    <div class="col-md-3">
        <label class="form-label small fw-semibold">Sort Order</label>
        <input type="number" name="sort_order" class="form-control form-control-sm"
               value="{{ old('sort_order', $item->sort_order ?? '') }}">
    </div>
</div>

@push('scripts')
<script>
(function(){
    const qty  = document.querySelector('[name="quantity"]');
    const rate = document.getElementById('rateInput');
    const amt  = document.getElementById('amountDisplay');
    function recalc(){
        const v = (parseFloat(qty.value)||0) * (parseFloat(rate.value)||0);
        amt.value = v.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
    }
    qty.addEventListener('input', recalc);
    rate.addEventListener('input', recalc);
})();
</script>
@endpush
