@extends('layouts.app')
@section('title', 'Edit Expense')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.expenses.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Expense</h4>
</div>
<div class="row"><div class="col-lg-6">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.expenses.update', [$project, $exp]) }}">
                @csrf @method('PUT')
                @include('expenses._form', ['expense' => $exp])
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-amber);">Save Changes</button>
                    <a href="{{ route('projects.expenses.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
