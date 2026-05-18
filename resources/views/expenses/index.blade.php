@extends('layouts.app')
@section('title', 'Expenses')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Expenses</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <a href="{{ route('projects.expenses.create', $project) }}" class="btn btn-sm text-white" style="background:var(--accent-amber);">
        <i class="bi bi-plus-lg me-1"></i>Add Expense
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach(['labour' => ['Labour', 'accent-blue'], 'machinery' => ['Machinery', 'accent-green'], 'other' => ['Other', 'accent-amber']] as $cat => [$label, $color])
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="h5 fw-bold mb-0" style="color:var(--{{ $color }});">₹{{ number_format($totals[$cat] / 100000, 2) }}L</div>
            <div class="small text-muted">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Category</th><th>Description</th><th class="text-end">Amount (₹)</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($expenses as $exp)
                <tr>
                    <td class="small">{{ $exp->date->format('d M Y') }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($exp->category) }}</span></td>
                    <td class="small">{{ $exp->description }}</td>
                    <td class="text-end small fw-semibold">{{ number_format($exp->amount, 2) }}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('projects.expenses.edit', [$project, $exp]) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('projects.expenses.destroy', [$project, $exp]) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No expenses recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $expenses->links() }}</div>
@endsection
