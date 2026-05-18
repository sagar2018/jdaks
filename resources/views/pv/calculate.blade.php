@extends('layouts.app')
@section('title', 'PV Calculation')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Price Variation Calculation — {{ $project->name }}</h4>
    <a href="{{ route('projects.pv.index', $project) }}" class="btn btn-sm btn-outline-secondary">Back</a>
</div>
@if($indices->isEmpty())
<div class="alert alert-warning small">No price indices available. <a href="{{ route('projects.pv.create', $project) }}">Add monthly indices</a> first.</div>
@else
<p class="text-muted small">Price variation indices available for {{ $indices->count() }} month(s). Full PV calculation with formula weights coming soon.</p>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr><th>Month</th><th class="text-end">Labour</th><th class="text-end">Cement</th><th class="text-end">Steel</th><th class="text-end">Bitumen</th></tr>
            </thead>
            <tbody>
                @foreach($indices as $idx)
                <tr>
                    <td class="small fw-semibold">{{ $idx->month }}</td>
                    <td class="text-end small">{{ $idx->labour_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->cement_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->steel_idx ?? '—' }}</td>
                    <td class="text-end small">{{ $idx->bitumen_idx ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
