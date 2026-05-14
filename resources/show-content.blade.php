<div class="message-thread custom-scrollbar" data-message-thread>
    @forelse($messages as $message)
        @php($isSender = (int) $message->sender_id === (int) $user->user_id)
        <div class="message-row {{ $isSender ? 'justify-content-end' : 'justify-content-start' }}">
            <div class="message-bubble {{ $isSender ? 'message-sent' : 'message-received' }}">
                <div class="message-meta">
                    <span class="message-sender">{{ optional($message->sender)->full_name ?? 'User' }}</span>
                    @if($message->sent_at)
                        <span>{{ \Carbon\Carbon::parse($message->sent_at)->diffForHumans() }}</span>
                    @endif
                </div>
                <div class="message-text">{{ $message->body_text }}</div>
            </div>
        </div>
    @empty
        <div class="empty-state py-5">
            <div class="empty-state-icon"><i class="bi bi-chat-left-text"></i></div>
            <p class="text-muted mb-0">No messages in this thread yet.</p>
        </div>
    @endforelse
</div>

<form method="POST" action="{{ route('messages.reply', $thread->thread_id) }}" class="message-compose mt-3">
    @csrf
    <div class="input-group message-reply">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-pencil-square text-muted"></i></span>
        <input type="text" name="body_text" class="form-control border-start-0" placeholder="Type your message..." required>
        <button class="btn btn-primary" type="submit">
            <span class="spinner-border spinner-border-sm me-2 d-none" aria-hidden="true"></span>
            <i class="bi bi-send me-1"></i>Send
        </button>
    </div>
</form>
