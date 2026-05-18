@extends('layouts.app')
@section('title', 'Progress Tracker')
@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <a href="{{ route('projects.hub', $project) }}" class="btn btn-sm btn-outline-secondary me-3" title="Back to Project">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Progress Tracker</h4>
            <p class="text-muted small mb-0">{{ $project->name }}</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.progress.report', $project) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-bar-chart me-1"></i>Report
        </a>
        <a href="{{ route('projects.progress.create', $project) }}" class="btn btn-sm text-white" style="background:var(--accent-green);">
            <i class="bi bi-plus-lg me-1"></i>Add Entry
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>BOQ Item</th>
                    <th class="text-end">Quantity</th>
                    <th>Chainage</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                <tr>
                    <td class="small">{{ $entry->date->format('d M Y') }}</td>
                    <td class="small">
                        <div class="fw-semibold">{{ $entry->boqItem->description }}</div>
                        <div class="text-muted">{{ $entry->boqItem->category }} · {{ $entry->boqItem->unit }}</div>
                    </td>
                    <td class="text-end small">{{ number_format($entry->quantity, 3) }}</td>
                    <td class="small text-muted">
                        @if($entry->chainage_from !== null)
                            {{ $entry->chainage_from }} – {{ $entry->chainage_to }}
                        @endif
                    </td>
                    <td>
                        @if($entry->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @else
                            <span class="badge bg-warning text-dark">Partial</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ Str::limit($entry->notes, 50) }}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('projects.progress.edit', [$project, $entry]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('projects.progress.destroy', [$project, $entry]) }}"
                                  onsubmit="return confirm('Delete this entry?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No progress entries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $entries->links() }}</div>
@endsection
