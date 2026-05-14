@props([
    'id',
    'class' => '',
    'exportable' => false,
])

<div class="table-responsive">
    <table id="{{ $id }}" class="table table-hover align-middle mb-0 js-datatable {{ $class }}" data-exportable="{{ $exportable ? '1' : '0' }}">
        {{ $slot }}
    </table>
</div>
