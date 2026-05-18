@extends('layouts.app')

@section('title', 'Assign Projects')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Assign Projects — {{ $user->name }}</h4>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.assign-projects.save', $user) }}">
                    @csrf

                    <p class="small text-muted mb-3">Select the projects this user can access:</p>

                    <div class="border rounded p-2 mb-4" style="max-height:300px;overflow-y:auto;">
                        @forelse($projects as $project)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="projects[]"
                                   value="{{ $project->id }}" id="p_{{ $project->id }}"
                                   @checked(in_array($project->id, $assignedIds))>
                            <label class="form-check-label small" for="p_{{ $project->id }}">
                                {{ $project->name }}
                                <span class="text-muted">— {{ $project->location ?? '' }}</span>
                            </label>
                        </div>
                        @empty
                        <p class="text-muted small mb-0">No projects created yet.</p>
                        @endforelse
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm text-white" style="background:var(--accent-blue);">
                            <i class="bi bi-check2 me-1"></i>Save Assignments
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
