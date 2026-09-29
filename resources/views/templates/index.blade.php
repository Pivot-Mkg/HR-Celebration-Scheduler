@extends('layouts.app')
@section('title', 'Email Templates')
@section('breadcrumb', 'Celebrations')

@section('content')
<div class="card">
    <div class="card-header">
        <i data-lucide="file-text"></i>Email Templates
        <a href="{{ route('templates.create') }}" class="btn btn-primary btn-sm ms-auto">
            <i data-lucide="circle-plus"></i>Create Template
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th><th>Type</th><th>Subject</th>
                        <th>Status</th><th>Default</th><th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $tpl)
                    <tr class="{{ $tpl->trashed() ? 'opacity-50' : '' }}">
                        <td class="fw-semibold">
                            {{ $tpl->name }}
                            @if($tpl->trashed())
                                <span class="badge rounded-pill ms-1" style="background:#f3f4f6;color:#6b7280;font-size:.7rem;">Archived</span>
                            @endif
                        </td>
                        <td>
                            @if($tpl->event_type === 'birthday')
                                <span class="badge rounded-pill" style="background:#fce7f3;color:#9d174d;font-size:.75rem;">🎂 Birthday</span>
                            @elseif($tpl->event_type === 'anniversary')
                                <span class="badge rounded-pill" style="background:#ede9fe;color:#5b21b6;font-size:.75rem;">🌟 Anniversary</span>
                            @else
                                <span class="badge rounded-pill" style="background:#dbeafe;color:#1e40af;font-size:.75rem;">🏢 Founding Day</span>
                            @endif
                        </td>
                        <td style="font-size:.84rem;color:#6b7280;">{{ Str::limit($tpl->subject, 50) }}</td>
                        <td>
                            @if(!$tpl->trashed())
                                <span class="badge rounded-pill px-2 {{ $tpl->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $tpl->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($tpl->is_default)
                                <span class="badge rounded-pill px-2" style="background:#fef3c7;color:#92400e;">✓ Default</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(!$tpl->trashed())
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('templates.show', $tpl) }}" class="btn btn-outline-secondary" title="View"><i data-lucide="eye"></i></a>
                                <a href="{{ route('templates.edit', $tpl) }}" class="btn btn-outline-primary" title="Edit"><i data-lucide="pencil"></i></a>
                                <a href="{{ route('templates.preview', $tpl) }}" class="btn btn-outline-info" title="Preview email"><i data-lucide="mail"></i></a>
                                @if(!$tpl->is_default)
                                <form method="POST" action="{{ route('templates.set-default', $tpl) }}" class="d-inline">@csrf
                                    <button class="btn btn-outline-warning" title="Set as Default"><i data-lucide="star"></i></button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('templates.duplicate', $tpl) }}" class="d-inline">@csrf
                                    <button class="btn btn-outline-secondary" title="Duplicate"><i data-lucide="copy"></i></button>
                                </form>
                                <form method="POST" action="{{ route('templates.destroy', $tpl) }}" class="d-inline" onsubmit="return confirm('Archive this template?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger" title="Archive"><i data-lucide="archive"></i></button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i data-lucide="file-x" class="icon-display"></i>
                            No templates yet. <a href="{{ route('templates.create') }}">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
