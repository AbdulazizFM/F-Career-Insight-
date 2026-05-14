@extends('layouts.dashboard')

@section('page-title', 'Messages')
@section('page-subtitle', 'Open conversation view')

@section('content')
    @php
        $receiver = null;
        if ((int) optional($thread->userOne)->user_id === (int) $user->user_id) {
            $receiver = $thread->userTwo;
        } elseif ((int) optional($thread->userTwo)->user_id === (int) $user->user_id) {
            $receiver = $thread->userOne;
        }
        $crumbMajor = optional(optional($thread->evaluation)->subMajor)->major->major_name ?? 'Major';
        $crumbSubMajor = optional(optional($thread->evaluation)->subMajor)->sub_major_name ?? 'Sub Major';
        $crumbRole = optional(optional($thread->evaluation)->role)->role_name ?? 'Role';
    @endphp
    <div class="messages-shell">
        <div class="messages-threads panel-card p-0 overflow-hidden">
            <div class="panel-head px-3 pt-3 pb-2 mb-0 border-bottom">
                <h5 class="mb-0">Threads</h5>
            </div>
            <div class="list-group list-group-flush messages-thread-list">
                <a href="{{ route('messages.show', $thread->thread_id) }}" class="list-group-item list-group-item-action active">
                    <div class="d-flex align-items-start gap-2">
                        <div class="thread-avatar">
                            <i class="bi bi-chat-left-dots"></i>
                        </div>
                        <div class="thread-main flex-grow-1">
                            <div class="fw-semibold thread-title">{{ optional($receiver)->full_name ?? 'Conversation' }}</div>
                            <small class="thread-preview">{{ $crumbMajor }} &gt; {{ $crumbSubMajor }} &gt; {{ $crumbRole }}</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="messages-chat panel-card p-0 overflow-hidden">
            <div class="panel-head px-3 pt-3 pb-2 mb-0 border-bottom">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <h5 class="mb-0">{{ optional($receiver)->full_name ?? 'Conversation' }}</h5>
                    <span class="badge bg-soft text-primary">{{ $messages->count() }} messages</span>
                </div>
            </div>
            <div class="panel-body p-3" data-chat-scroll>
                @include('messages.show-content')
            </div>
        </div>
    </div>
@endsection
