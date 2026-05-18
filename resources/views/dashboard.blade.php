@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <p class="text-muted small mb-0">Welcome back, {{ auth()->user()->name }}</p>
    </div>
    @can('create_projects')
    <a href="{{ route('projects.create') }}" class="btn btn-sm text-white" style="background:var(--accent-blue);">
        <i class="bi bi-plus-lg me-1"></i>New Project
    </a>
    @endcan
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:44px;height:44px;background:rgba(30,64,175,.1);">
                    <i class="bi bi-folder2-open" style="color:var(--accent-blue);font-size:1.2rem;"></i>
                </div>
                <div>
                    <div class="h4 mb-0 fw-bold">{{ $stats['total_projects'] }}</div>
                    <div class="small text-muted">Total Projects</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($projects->count())
<h6 class="fw-semibold text-muted mb-3 text-uppercase small">Recent Projects</h6>
<div class="row g-3">
    @foreach($projects as $project)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <h6 class="fw-bold mb-0">{{ $project->name }}</h6>
                    <span class="badge bg-light text-secondary border small">{{ $project->package ?? '—' }}</span>
                </div>
                <p class="small text-muted mb-3">{{ Str::limit($project->location, 60) }}</p>
                <div class="row text-center g-0 border-top pt-3">
                    <div class="col">
                        <div class="fw-bold small">{{ $project->boq_items_count }}</div>
                        <div class="text-muted" style="font-size:.7rem;">BOQ Items</div>
                    </div>
                    <div class="col border-start">
                        <div class="fw-bold small">{{ $project->ra_bills_count }}</div>
                        <div class="text-muted" style="font-size:.7rem;">RA Bills</div>
                    </div>
                    <div class="col border-start">
                        <div class="fw-bold small" style="color:var(--accent-green);">{{ $project->completion_pct }}%</div>
                        <div class="text-muted" style="font-size:.7rem;">Complete</div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-4">
                <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm w-100 btn-outline-primary">
                    Open Project Hub
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-5">
    <i class="bi bi-folder-plus" style="font-size:3rem;color:#CBD5E1;"></i>
    <p class="text-muted mt-2">No projects yet. Create your first project to get started.</p>
    @can('create_projects')
    <a href="{{ route('projects.create') }}" class="btn text-white" style="background:var(--accent-blue);">
        <i class="bi bi-plus-lg me-1"></i>Create First Project
    </a>
    @endcan
</div>
@endif
@endsection
