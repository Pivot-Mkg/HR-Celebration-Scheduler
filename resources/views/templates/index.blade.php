@extends('layouts.app')
@section('title', 'Email Templates')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2"></i>Email Templates</span>
        <a href="{{ route('templates.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Create Template</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Default</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $tpl)
                    <tr class="{{ $tpl->trashed() ? 'text-muted' : '' }}">
                        <td class="fw-semibold">
                            {{ $tpl->name }}
                            @if($tpl->trashed()) <span class="badge bg-secondary ms-1">Archived</span> @endif
                        </td>
                        <td>
                            @if($tpl->event_type === 'birthday')
                                <span class="badge" style="background:#fce4ec;color:#c62828;">🎂 Birthday</span>
                            @else
                                <span class="badge" style="background:#e8eaf6;color:#1a237e;">🌟 Anniversary</span>
                            @endif
                        </td>
                        <td>{{ Str::limit($tpl->subject, 50) }}</td>
                        <td>
                            @if(!$tpl->trashed())
                                <span class="badge {{ $tpl->is_active ? 'badge-active' : 'badge-inactive' }} rounded-pill px-2">
                                    {{ $tpl->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($tpl->is_default)
                                <span class="badge bg-warning text-dark rounded-pill px-2">✓ Default</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(!$tpl->trashed())
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('templates.show', $tpl) }}" class="btn btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('templates.edit', $tpl) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('templates.preview', $tpl) }}" class="btn btn-outline-info" title="Preview Email"><i class="bi bi-envelope"></i></a>
                                @if(!$tpl->is_default)
                                <form method="POST" action="{{ route('templates.set-default', $tpl) }}" class="d-inline">@csrf
                                    <button class="btn btn-outline-warning" title="Set as Default"><i class="bi bi-star"></i></button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('templates.duplicate', $tpl) }}" class="d-inline">@csrf
                                    <button class="btn btn-outline-secondary" title="Duplicate"><i class="bi bi-copy"></i></button>
                                </form>
                                <form method="POST" action="{{ route('templates.destroy', $tpl) }}" class="d-inline" onsubmit="return confirm('Archive this template?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger" title="Archive"><i class="bi bi-archive"></i></button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-file-earmark-x fs-2 d-block mb-2"></i>No templates yet.
                        <a href="{{ route('templates.create') }}">Create one</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
