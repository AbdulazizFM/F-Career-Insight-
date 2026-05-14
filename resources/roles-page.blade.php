@extends('layouts.admin')

@section('page-title', 'Roles')
@section('page-subtitle', 'Manage roles')

@section('content')
    <x-page-header title="Roles" subtitle="Create, edit, and manage roles" :breadcrumbs="[['label' => 'Admin Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Roles']]">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">Add Role</button>
    </x-page-header>

    <div class="row g-4">
        @include('admin.catalog.roles')
    </div>
@endsection
