@extends('layouts.admin')

@section('page-title', 'Majors')
@section('page-subtitle', 'Manage majors')

@section('content')
    <x-page-header title="Majors" subtitle="Create, edit, and manage majors" :breadcrumbs="[['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Majors']]">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMajorModal">Add Major</button>
    </x-page-header>

    <div class="row g-4">
        @include('admin.catalog.majors')
    </div>
@endsection
