@extends('layouts.app')
@section('title', $template->name)
@section('breadcrumb', '<a href="' . route('templates.index') . '">Templates</a> / ' . e($template->name))

@section('content')
<div class="row g-3">
<div class="col-lg-8">
    <div class="card mb-3">
        <div class="card-header">
            <i data-lucide="file-text"></i>{{ $template->name }}
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('templates.edit', $template) }}" class="btn btn-sm btn-primary"><i data-lucide="pencil"></i>Edit</a>
                <a href="{{ route('templates.preview', $template) }}" class="btn btn-sm btn-outline-info"><i data-lucide="mail"></i>Preview</a>
                <a href="{{ route('templates.index') }}" class="btn btn-sm btn-outline-secondary"><i data-lucide="arrow-left"></i>Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Type</div>
                    <div class="mt-1">
                        @if($template->event_type === 'birthday') 🎂 Birthday
                        @elseif($template->event_type === 'anniversary') 🌟 Anniversary
                        @else 🏢 Founding Day
                        @endif
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                    <div class="mt-1 d-flex gap-1 flex-wrap">
                        <span class="badge rounded-pill px-2 {{ $template->is_active ? 'badge-active' : 'badge-inactive' }}">{{ $template->is_active ? 'Active' : 'Inactive' }}</span>
                        @if($template->is_default)<span class="badge rounded-pill px-2" style="background:#fef3c7;color:#92400e;">Default</span>@endif
                    </div>
                </div>
                @if($template->from_email)
                <div class="col-sm-4">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">From</div>
                    <div class="mt-1" style="font-size:.85rem;">{{ $template->from_name }} &lt;{{ $template->from_email }}&gt;</div>
                </div>
                @endif
                <div class="col-12">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Subject</div>
                    <div class="fw-semibold mt-1">{{ $template->subject }}</div>
                </div>
                @if($template->cc_addresses)
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">CC</div>
                    <div class="mt-1" style="font-size:.85rem;">{{ $template->cc_addresses }}</div>
                </div>
                @endif
                @if($template->bcc_addresses)
                <div class="col-sm-6">
                    <div class="text-muted" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">BCC</div>
                    <div class="mt-1" style="font-size:.85rem;">{{ $template->bcc_addresses }}</div>
                </div>
                @endif
            </div>
            <div class="text-muted mb-2" style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;">HTML Body Preview</div>
            <div style="border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;max-height:400px;overflow-y:auto;">
                {!! $template->body_html !!}
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="card mb-3">
        <div class="card-header"><i data-lucide="zap"></i>Actions</div>
        <div class="card-body d-flex flex-column gap-2">
            @if(!$template->is_default)
            <form method="POST" action="{{ route('templates.set-default', $template) }}">@csrf
                <button class="btn w-100" style="background:#fef3c7;color:#92400e;border:1px solid #fde68a;">
                    <i data-lucide="star"></i>Set as Default
                </button>
            </form>
            @else
            <div class="alert alert-warning mb-0 py-2 small d-flex align-items-center gap-2">
                <i data-lucide="star"></i>This is the default template.
            </div>
            @endif
            <form method="POST" action="{{ route('templates.duplicate', $template) }}">@csrf
                <button class="btn btn-outline-secondary w-100"><i data-lucide="copy"></i>Duplicate</button>
            </form>
            <form method="POST" action="{{ route('templates.destroy', $template) }}" onsubmit="return confirm('Archive this template?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger w-100"><i data-lucide="archive"></i>Archive</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
