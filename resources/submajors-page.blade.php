@extends('layouts.admin')

@section('page-title', 'Sub Majors')
@section('page-subtitle', 'Manage sub majors')

@section('content')
    <x-page-header title="Sub Majors" subtitle="Create, edit, and manage sub majors (Total: {{ $subMajors->count() }})" :breadcrumbs="[['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Sub Majors']]">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubMajorModal">Add Sub Major</button>
    </x-page-header>

    <div class="row g-4">
        @include('admin.catalog.submajors')
    </div>
@endsection
