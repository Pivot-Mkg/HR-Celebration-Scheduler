@extends('layouts.app')
@section('title', 'Add Employee')
@section('breadcrumb', '<a href="' . route('employees.index') . '">Employees</a> / Add')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-8">
<div class="card">
    <div class="card-header">
        <i data-lucide="user-plus"></i>Add Employee
        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
            <i data-lucide="arrow-left"></i>Back
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            @include('employees._form')
            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i data-lucide="check"></i>Save Employee</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
