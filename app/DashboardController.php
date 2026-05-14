<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\Evaluation;
use App\Models\JobPurchase;
use App\Models\Major;
use App\Models\Message;
use App\Models\Role;
use App\Models\SubMajor;
use App\Models\Subscription;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $user = $this->currentUser();

        $subscriptions = Subscription::with('payment')
            ->where('user_id', $user->user_id)
            ->orderByDesc('subscription_id')
            ->get();
        $currentSubscription = $subscriptions->first();
        $activeSubscription = $subscriptions->first(function (Subscription $subscription) {
            return $subscription->isActiveNow();
        }) ?? $subscriptions->first(function (Subscription $subscription) {
            return strtolower((string) $subscription->status) === 'active';
        });

        $purchasedJobsCount = JobPurchase::where('user_id', $user->user_id)->count();
        $messagesCount = Message::where('sender_id', $user->user_id)->count();
        $accessibleJobsCount = $this->accessibleJobsCount($activeSubscription, $user->user_id);
        $shortlistedJobsCount = JobPurchase::where('user_id', $user->user_id)
            ->whereDate('purchase_date', '>=', Carbon::now()->subDays(60)->toDateString())
            ->count();

        $recentPurchases = JobPurchase::with(['subMajor.major', 'payment'])
            ->where('user_id', $user->user_id)
            ->latest('purchase_id')
            ->take(8)
            ->get();

        $recommendedJobs = $this->recommendedJobs($user->user_id);
        $unlockedEvaluations = $this->unlockedEvaluations($user->user_id, $activeSubscription);
        $subscriptionInsight = $this->subscriptionInsight($currentSubscription, $activeSubscription, $purchasedJobsCount);

        $recentMessages = Message::with(['thread.evaluation.subMajor.major', 'sender'])
            ->where('sender_id', $user->user_id)
            ->latest('message_id')
            ->take(6)
            ->get();

        $monthlyActivity = $this->buildMonthlyActivitySeries($user->user_id);
        $majorInterest = $this->majorInterestBreakdown($user->user_id);

        return view('dashboard.index', compact(
            'user',
            'currentSubscription',
            'purchasedJobsCount',
            'messagesCount',
            'accessibleJobsCount',
            'shortlistedJobsCount',
            'recommendedJobs',
            'unlockedEvaluations',
            'recentMessages',
            'recentPurchases',
            'monthlyActivity',
            'majorInterest',
            'activeSubscription',
            'subscriptionInsight'
        ));
    }

    public function majors()
    {
        $this->currentUser();

        $majors = Major::withCount('subMajors')
            ->with(['subMajors' => function ($query) {
                $query->select('sub_major_id', 'major_id', 'sub_major_name')->orderBy('sub_major_name');
            }])
            ->orderBy('major_name')
            ->get();

        return view('dashboard.majors', compact('majors'));
    }

    public function majorSubMajors(int $majorId)
    {
        $this->currentUser();

        $major = Major::with(['subMajors' => function ($query) {
            $query->withCount('evaluations')->orderBy('sub_major_name');
        }])->findOrFail($majorId);

        return view('dashboard.major-submajors', compact('major'));
    }

    public function subMajorRoles(int $subMajorId)
    {
        $this->currentUser();

        $subMajor = SubMajor::with(['major', 'roles' => function ($query) {
            $query->withCount('evaluations')->orderBy('role_name');
        }])->findOrFail($subMajorId);

        return view('dashboard.submajor-roles', compact('subMajor'));
    }

    public function roleDetails(int $roleId)
    {
        $user = $this->currentUser();

        $role = Role::with('subMajor.major')->findOrFail($roleId);

        $currentSubscription = Subscription::where('user_id', $user->user_id)
            ->activeNow()
            ->latest('subscription_id')
            ->first();

        $purchased = JobPurchase::where('user_id', $user->user_id)
            ->where('sub_major_id', $role->sub_major_id)
            ->exists();

        $accessGranted = (bool) $currentSubscription || $purchased;

        $evaluations = Evaluation::with('user')
            ->where('role_id', $role->role_id)
            ->latest('evaluation_id')
            ->paginate(8);

        $averageRating = round((float) Evaluation::where('role_id', $role->role_id)->avg('rating'), 1);
        $evaluationCount = Evaluation::where('role_id', $role->role_id)->count();

        $myEvaluation = Evaluation::where('user_id', $user->user_id)
            ->where('role_id', $role->role_id)
            ->latest('evaluation_id')
            ->first();

        $similarRoles = Role::where('sub_major_id', $role->sub_major_id)
            ->where('role_id', '!=', $role->role_id)
            ->orderBy('role_name')
            ->take(6)
            ->get();

        if (! $accessGranted) {
            $evaluations = collect();
        }

        return view('dashboard.role-details', compact(
            'role',
            'evaluations',
            'averageRating',
            'evaluationCount',
            'myEvaluation',
            'similarRoles',
            'accessGranted',
            'currentSubscription',
            'purchased'
        ));
    }

    protected function buildMonthlyActivitySeries($userId): array
    {
        $labels = [];
        $discoveries = [];
        $purchases = [];
        $messages = [];

        for ($offset = 5; $offset >= 0; $offset--) {
            $start = Carbon::now()->startOfMonth()->subMonths($offset);
            $end = (clone $start)->endOfMonth();
            $labels[] = $start->format('M');

            $discoveries[] = Evaluation::whereHas('subMajor', function ($query) use ($userId) {
                    $query->whereIn('sub_major_id', JobPurchase::where('user_id', $userId)->pluck('sub_major_id'));
                })
                ->whereBetween('created_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                ->count();

            $purchases[] = JobPurchase::where('user_id', $userId)
                ->whereBetween('purchase_date', [$start->toDateTimeString(), $end->toDateTimeString()])
                ->count();

            $messages[] = Message::where('sender_id', $userId)
                ->whereBetween('sent_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                ->count();
        }

        return [
            'labels' => $labels,
            'discoveries' => $discoveries,
            'purchases' => $purchases,
            'messages' => $messages,
        ];
    }

    protected function accessibleJobsCount(?Subscription $subscription, int $userId): int
    {
        if ($subscription && strtolower((string) $subscription->status) === 'active' && strtolower((string) $subscription->plan_type) === 'monthly') {
            return SubMajor::count();
        }

        return JobPurchase::where('user_id', $userId)->distinct('sub_major_id')->count('sub_major_id');
    }

    protected function recommendedJobs(int $userId)
    {
        $preferredMajorIds = JobPurchase::with('subMajor')
            ->where('user_id', $userId)
            ->get()
            ->pluck('subMajor.major_id')
            ->filter()
            ->unique()
            ->values();

        $query = SubMajor::with('major')
            ->withCount('evaluations')
            ->withAvg('evaluations as average_rating', 'rating');

        if ($preferredMajorIds->isNotEmpty()) {
            $query->whereIn('major_id', $preferredMajorIds);
        }

        return $query->orderByDesc('evaluations_count')
            ->orderByDesc('sub_major_id')
            ->take(6)
            ->get();
    }

    protected function unlockedEvaluations(int $userId, ?Subscription $subscription)
    {
        $query = Evaluation::with(['subMajor.major', 'user'])
            ->where('user_id', '!=', $userId);

        if ($subscription && strtolower((string) $subscription->status) === 'active' && strtolower((string) $subscription->plan_type) === 'monthly') {
            return $query->latest('evaluation_id')->take(6)->get();
        }

        $purchasedSubMajors = JobPurchase::where('user_id', $userId)->pluck('sub_major_id');
        if ($purchasedSubMajors->isEmpty()) {
            return collect();
        }

        return $query->whereIn('sub_major_id', $purchasedSubMajors)
            ->latest('evaluation_id')
            ->take(6)
            ->get();
    }

    protected function majorInterestBreakdown(int $userId): array
    {
        $purchases = JobPurchase::with('subMajor.major')
            ->where('user_id', $userId)
            ->get();

        $labels = [];
        $values = [];

        $grouped = $purchases->groupBy(function ($purchase) {
            return optional(optional($purchase->subMajor)->major)->major_name ?: 'Other';
        })->sortByDesc(function ($items) {
            return $items->count();
        })->take(6);

        foreach ($grouped as $name => $items) {
            $labels[] = $name;
            $values[] = $items->count();
        }

        if (empty($labels)) {
            $labels = ['No Data'];
            $values = [1];
        }

        return compact('labels', 'values');
    }

    protected function subscriptionInsight(?Subscription $currentSubscription, ?Subscription $activeSubscription, int $purchasedJobsCount = 0): array
    {
        if (! $currentSubscription) {
            if ($purchasedJobsCount > 0) {
                return [
                    'state' => 'partial',
                    'message' => 'No active monthly subscription, but you still have access to your purchased roles.',
                    'daysRemaining' => null,
                ];
            }

            return [
                'state' => 'none',
                'message' => 'You are not subscribed yet. Subscribe to unlock all job titles and full evaluations.',
                'daysRemaining' => null,
            ];
        }

        if ($activeSubscription) {
            $daysRemaining = Carbon::today()->diffInDays(Carbon::parse($activeSubscription->end_date), false) + 1;
            return [
                'state' => 'active',
                'message' => 'Your subscription is active.',
                'daysRemaining' => max(0, $daysRemaining),
            ];
        }

        return [
            'state' => 'expired',
            'message' => 'Your subscription has expired. Renew to restore full access.',
            'daysRemaining' => 0,
        ];
    }
}
