@extends('layouts.app')
@section('title', 'Expense Report')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Expense Report — {{ $project->name }}</h4>
    <a href="{{ route('projects.expenses.index', $project) }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>
@php
$grouped = $expenses->groupBy('category');
@endphp
@foreach($grouped as $cat => $items)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-transparent fw-semibold d-flex justify-content-between">
        <span>{{ ucfirst($cat) }}</span>
        <span class="fw-bold">₹{{ number_format($items->sum('amount'), 2) }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Description</th><th class="text-end">Amount (₹)</th></tr></thead>
            <tbody>
                @foreach($items as $exp)
                <tr>
                    <td class="small">{{ $exp->date->format('d M Y') }}</td>
                    <td class="small">{{ $exp->description }}</td>
                    <td class="text-end small">{{ number_format($exp->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection
