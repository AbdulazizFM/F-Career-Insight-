<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\Evaluation;
use App\Models\Message;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $user = $this->currentUser();

        $myInsights = Evaluation::with(['subMajor.major'])
            ->where('user_id', $user->user_id)
            ->latest('evaluation_id')
            ->get();

        $insightsCount = $myInsights->count();
        $evaluationIds = $myInsights->pluck('evaluation_id');
        $threadIds = \App\Models\MessageThread::whereIn('evaluation_id', $evaluationIds)->pluck('thread_id');

        $messagesCount = Message::whereIn('thread_id', function ($q) use ($user) {
            $q->select('thread_id')
                ->from('MESSAGETHREAD')
                ->whereIn('evaluation_id', Evaluation::where('user_id', $user->user_id)->pluck('evaluation_id'));
        })->count();

        $incomingMessagesCount = Message::whereIn('thread_id', $threadIds)
            ->where('sender_id', '!=', $user->user_id)
            ->count();

        $unansweredThreadsCount = Message::whereIn('thread_id', $threadIds)
            ->selectRaw('thread_id, MAX(CASE WHEN sender_id = ? THEN sent_at END) as last_reply_at, MAX(CASE WHEN sender_id != ? THEN sent_at END) as last_question_at', [$user->user_id, $user->user_id])
            ->groupBy('thread_id')
            ->get()
            ->filter(function ($row) {
                if (! $row->last_question_at) {
                    return false;
                }

                return ! $row->last_reply_at || $row->last_question_at > $row->last_reply_at;
            })
            ->count();

        $avgRating = round((float) Evaluation::where('user_id', $user->user_id)->avg('rating'), 1);
        $topInsight = $myInsights->sortByDesc('rating')->first();
        $recentQuestions = Message::with(['sender', 'thread.evaluation.subMajor.major'])
            ->whereIn('thread_id', $threadIds)
            ->where('sender_id', '!=', $user->user_id)
            ->latest('message_id')
            ->take(6)
            ->get();

        $profileCompleteness = $this->profileCompleteness($user);
        $monthlyEngagement = $this->monthlyEngagementSeries($user->user_id);
        $impactByMajor = $this->impactByMajor($myInsights);

        return view('employee.dashboard', compact(
            'user',
            'myInsights',
            'insightsCount',
            'messagesCount',
            'incomingMessagesCount',
            'unansweredThreadsCount',
            'avgRating',
            'topInsight',
            'recentQuestions',
            'profileCompleteness',
            'monthlyEngagement',
            'impactByMajor'
        ));
    }

    protected function profileCompleteness($user): int
    {
        $fields = [
            (bool) $user->full_name,
            (bool) $user->email,
            (bool) $user->job_title,
            (bool) $user->company,
            $user->years_experience !== null,
        ];

        $complete = collect($fields)->filter()->count();
        return (int) round(($complete / count($fields)) * 100);
    }

    protected function monthlyEngagementSeries(int $userId): array
    {
        $labels = [];
        $insights = [];
        $questions = [];

        $evaluationIds = Evaluation::where('user_id', $userId)->pluck('evaluation_id');
        $threadIds = \App\Models\MessageThread::whereIn('evaluation_id', $evaluationIds)->pluck('thread_id');

        for ($offset = 5; $offset >= 0; $offset--) {
            $start = Carbon::now()->startOfMonth()->subMonths($offset);
            $end = (clone $start)->endOfMonth();
            $labels[] = $start->format('M');

            $insights[] = Evaluation::where('user_id', $userId)
                ->whereBetween('created_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                ->count();

            $questions[] = Message::whereIn('thread_id', $threadIds)
                ->where('sender_id', '!=', $userId)
                ->whereBetween('sent_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                ->count();
        }

        return compact('labels', 'insights', 'questions');
    }

    protected function impactByMajor($insights): array
    {
        $labels = [];
        $values = [];

        $grouped = $insights->groupBy(function ($insight) {
            return optional(optional($insight->subMajor)->major)->major_name ?: 'Other';
        })->sortByDesc(function ($items) {
            return $items->count();
        })->take(6);

        foreach ($grouped as $major => $items) {
            $labels[] = $major;
            $values[] = $items->count();
        }

        if (empty($labels)) {
            $labels = ['No Data'];
            $values = [1];
        }

        return compact('labels', 'values');
    }
}
