@extends('layouts.app')
@section('title', 'Create Template')
@section('breadcrumb', '<a href="' . route('templates.index') . '">Templates</a> / Create')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">
<div class="card">
    <div class="card-header">
        <i data-lucide="circle-plus"></i>Create Email Template
        <a href="{{ route('templates.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
            <i data-lucide="arrow-left"></i>Back
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('templates.store') }}" enctype="multipart/form-data">
            @csrf
            @include('templates._form')
            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i data-lucide="check"></i>Save Template</button>
                <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
