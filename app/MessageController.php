<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\Evaluation;
use App\Models\Message;
use App\Models\MessageThread;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $user = $this->currentUser();

        $threads = MessageThread::with([
                'evaluation.subMajor.major',
                'evaluation.role',
                'userOne',
                'userTwo',
                'messages' => function ($query) {
                    $query->latest('sent_at');
                },
            ])
            ->where(function ($query) use ($user) {
                $query->where('user_one_id', $user->user_id)
                    ->orWhere('user_two_id', $user->user_id)
                    ->orWhereHas('messages', function ($messagesQuery) use ($user) {
                        $messagesQuery->where('sender_id', $user->user_id);
                    });
            })
            ->latest('thread_id')
            ->get();

        $thread = $threads->first();
        $messages = $thread ? $thread->messages->sortBy('sent_at')->values() : collect();

        return view('messages.index', compact('threads', 'thread', 'messages', 'user'));
    }

    public function show($threadId)
    {
        $user = $this->currentUser();

        $thread = MessageThread::with(['evaluation.subMajor.major', 'evaluation.role', 'userOne', 'userTwo', 'messages.sender'])->findOrFail($threadId);
        abort_unless($this->canAccessThread($thread, $user->user_id), 403);

        $messages = $thread->messages->sortBy('sent_at')->values();

        return view('messages.show', compact('thread', 'messages', 'user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'thread_id' => ['nullable', 'exists:MESSAGETHREAD,thread_id'],
            'evaluation_id' => ['nullable', 'exists:EVALUATION,evaluation_id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_text' => ['required', 'string'],
        ]);

        $user = $this->currentUser();
        $thread = null;

        if (! empty($data['thread_id'])) {
            $thread = MessageThread::with(['evaluation', 'messages'])->findOrFail($data['thread_id']);
            abort_unless($this->canAccessThread($thread, $user->user_id), 403);
        } else {
            abort_unless(! empty($data['evaluation_id']), 422, 'Evaluation is required when starting a new thread.');
            $evaluationThread = Evaluation::findOrFail($data['evaluation_id']);
            if ((int) $evaluationThread->user_id === (int) $user->user_id) {
                abort(422, 'You cannot start a thread on your own evaluation.');
            }

            $userOneId = min((int) $evaluationThread->user_id, (int) $user->user_id);
            $userTwoId = max((int) $evaluationThread->user_id, (int) $user->user_id);

            $thread = MessageThread::firstOrCreate([
                'evaluation_id' => $data['evaluation_id'],
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
            ], [
                'created_at' => now(),
            ]);
        }

        Message::create([
            'thread_id' => $thread->thread_id,
            'sender_id' => $user->user_id,
            'subject' => $data['subject'] ?? null,
            'body_text' => $data['body_text'],
            'sent_at' => now(),
        ]);

        return redirect()->route('messages.show', $thread->thread_id)->with('success', 'Message sent successfully.');
    }

    public function reply(Request $request, $threadId)
    {
        $request->merge([
            'thread_id' => $threadId,
            'evaluation_id' => null,
        ]);

        return $this->store($request);
    }

    protected function canAccessThread(MessageThread $thread, int $userId): bool
    {
        if ((int) $thread->user_one_id === $userId || (int) $thread->user_two_id === $userId) {
            return true;
        }

        // Backward compatibility for old threads before participant columns were filled
        $ownsEvaluation = $thread->evaluation && (int) $thread->evaluation->user_id === $userId;
        $isParticipant = $thread->messages->contains(function ($message) use ($userId) {
            return (int) $message->sender_id === $userId;
        });

        return $ownsEvaluation || $isParticipant;
    }
}
