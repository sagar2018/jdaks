@extends('layouts.app')
@section('title', 'Add PV Index')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('projects.pv.index', $project) }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Add Price Variation Index</h4>
</div>
<div class="row"><div class="col-lg-7">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('projects.pv.store', $project) }}">
                @csrf
                @include('pv._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-red);">Save Index</button>
                    <a href="{{ route('projects.pv.index', $project) }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
@endsection
