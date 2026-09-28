@extends('layouts.app')
@section('title', 'Create Template')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-10">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plus-circle me-2"></i>Create Email Template</span>
        <a href="{{ route('templates.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('templates.store') }}">
            @csrf
            @include('templates._form')
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Template</button>
                <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
