@extends('layouts.app')
@section('title', $template->name)

@section('content')
<div class="row">
<div class="col-lg-8">
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2"></i>{{ $template->name }}</span>
        <div class="d-flex gap-2">
            <a href="{{ route('templates.edit', $template) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>
            <a href="{{ route('templates.preview', $template) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-envelope me-1"></i>Preview</a>
            <a href="{{ route('templates.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-sm-6"><div class="text-muted small">Type</div><div>{{ $template->event_type === 'birthday' ? '🎂 Birthday' : '🌟 Anniversary' }}</div></div>
            <div class="col-sm-6"><div class="text-muted small">Status</div>
                <span class="badge {{ $template->is_active ? 'badge-active' : 'badge-inactive' }} rounded-pill px-2">{{ $template->is_active ? 'Active' : 'Inactive' }}</span>
                @if($template->is_default) <span class="badge bg-warning text-dark rounded-pill px-2 ms-1">Default</span> @endif
            </div>
            <div class="col-12"><div class="text-muted small">Subject</div><div class="fw-semibold">{{ $template->subject }}</div></div>
            @if($template->from_email)<div class="col-sm-6"><div class="text-muted small">From</div><div>{{ $template->from_name }} &lt;{{ $template->from_email }}&gt;</div></div>@endif
            @if($template->cc_addresses)<div class="col-sm-6"><div class="text-muted small">CC</div><div>{{ $template->cc_addresses }}</div></div>@endif
            @if($template->bcc_addresses)<div class="col-sm-6"><div class="text-muted small">BCC</div><div>{{ $template->bcc_addresses }}</div></div>@endif
        </div>
        <div class="text-muted small fw-semibold mb-1">HTML Body Preview</div>
        <div style="border:1px solid #dee2e6; border-radius:6px; overflow:hidden; max-height:400px; overflow-y:auto;">
            {!! $template->body_html !!}
        </div>
    </div>
</div>
</div>
<div class="col-lg-4">
<div class="card mb-3">
    <div class="card-header">Actions</div>
    <div class="card-body d-flex flex-column gap-2">
        @if(!$template->is_default)
        <form method="POST" action="{{ route('templates.set-default', $template) }}">@csrf
            <button class="btn btn-warning w-100"><i class="bi bi-star me-2"></i>Set as Default</button>
        </form>
        @endif
        <form method="POST" action="{{ route('templates.duplicate', $template) }}">@csrf
            <button class="btn btn-outline-secondary w-100"><i class="bi bi-copy me-2"></i>Duplicate</button>
        </form>
        <form method="POST" action="{{ route('templates.destroy', $template) }}" onsubmit="return confirm('Archive this template?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger w-100"><i class="bi bi-archive me-2"></i>Archive</button>
        </form>
    </div>
</div>
</div>
</div>
@endsection
