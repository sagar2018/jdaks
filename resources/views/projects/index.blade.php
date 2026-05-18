@extends('layouts.app')

@section('title', 'Projects Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Projects</h4>
        <p class="text-muted small mb-0">
            {{ $projects->count() }} project{{ $projects->count() !== 1 ? 's' : '' }}
        </p>
    </div>
    @can('create projects')
    <a href="{{ route('projects.create') }}" class="btn btn-sm text-white" style="background:var(--accent-blue);">
        <i class="bi bi-plus-lg me-1"></i>New Project
    </a>
    @endcan
</div>

@if($projects->isEmpty())
<div class="text-center py-5">
    <i class="bi bi-folder2-open display-4 text-muted"></i>
    <p class="mt-3 text-muted">No projects yet.
        @can('create projects')
            <a href="{{ route('projects.create') }}">Create your first project.</a>
        @endcan
    </p>
</div>
@else
<div class="row g-3">
    @foreach($projects as $project)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100 module-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold mb-0" style="color:var(--accent-blue);">{{ $project->name }}</h6>
                    @if($project->package)
                        <span class="badge bg-light text-secondary border">{{ $project->package }}</span>
                    @endif
                </div>

                @if($project->location)
                <p class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $project->location }}</p>
                @endif

                @if($project->contractor)
                <p class="small text-muted mb-2"><i class="bi bi-building me-1"></i>{{ $project->contractor }}</p>
                @endif

                {{-- Progress bar --}}
                @php $pct = round($project->completion_pct, 1); @endphp
                <div class="mt-3 mb-1">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Progress</span>
                        <span class="fw-semibold">{{ $pct }}%</span>
                    </div>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar @if($pct >= 100) bg-success @elseif($pct >= 50) bg-warning @else bg-primary @endif"
                             role="progressbar" style="width:{{ min($pct, 100) }}%"></div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-2 small text-muted">
                    <span><i class="bi bi-list-ul me-1"></i>{{ $project->boqItems_count ?? 0 }} items</span>
                    @if($project->deadline_date)
                        <span><i class="bi bi-calendar-event me-1"></i>{{ $project->deadline_date->format('d M Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm w-100 btn-outline-primary">
                    Open Project Hub
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
