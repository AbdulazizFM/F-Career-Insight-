<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\Complaint;
use App\Models\Evaluation;
use App\Models\JobPurchase;
use App\Models\Payment;
use App\Models\Report as ReportModel;
use App\Models\ReportFlag;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', \App\Models\Payment::class);

        $stats = [
            'totalUsers' => User::count(),
            'activeUsers' => User::whereRaw('LOWER(account_status) = ?', ['active'])->count(),
            'suspendedUsers' => User::whereRaw('LOWER(account_status) = ?', ['suspended'])->count(),
            'totalRevenue' => Payment::whereRaw('LOWER(status) = ?', ['completed'])->sum('amount'),
            'totalSubscriptions' => Subscription::count(),
            'totalPurchases' => JobPurchase::count(),
            'totalPayments' => Payment::count(),
            'pendingComplaints' => Complaint::whereRaw('LOWER(status) != ?', ['resolved'])->count(),
            'pendingReportFlags' => ReportFlag::whereRaw('LOWER(status) != ?', ['reviewed'])->count(),
            'totalEvaluations' => Evaluation::count(),
            'expiredSubscriptions' => Subscription::whereDate('end_date', '<', Carbon::today()->toDateString())->count(),
            'expiringSoonSubscriptions' => Subscription::whereDate('end_date', '>=', Carbon::today()->toDateString())
                ->whereDate('end_date', '<=', Carbon::today()->addDays(7)->toDateString())
                ->count(),
        ];

        $recentUsers = User::latest('user_id')->take(5)->get();
        $recentReports = ReportModel::with('admin')->latest('report_id')->take(5)->get();
        $recentComplaints = Complaint::with(['user', 'admin'])->latest('complaint_id')->take(5)->get();
        $recentEvaluations = Evaluation::with(['user', 'subMajor.major'])->latest('evaluation_id')->take(5)->get();
        $expiringSubscriptions = Subscription::with('user')
            ->whereDate('end_date', '>=', Carbon::today()->toDateString())
            ->whereDate('end_date', '<=', Carbon::today()->addDays(7)->toDateString())
            ->orderBy('end_date')
            ->take(5)
            ->get();
        $adminActivity = $this->buildAdminActivitySeries();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentReports', 'recentComplaints', 'recentEvaluations', 'expiringSubscriptions', 'adminActivity'));
    }

    public function payments()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', \App\Models\Payment::class);

        $payments = Payment::with(['subscription.user', 'jobPurchase.subMajor.major'])
            ->latest('payment_id')
            ->get();

        return view('admin.payments', compact('payments'));
    }

    public function verifyPayment($id)
    {
        $actor = $this->requireUser();
        $payment = Payment::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $payment);
        $payment->status = 'Completed';
        $payment->payment_date = now();
        $payment->save();

        return redirect()->route('admin.payments')->with('success', 'Payment verified successfully.');
    }

    public function rejectPayment($id)
    {
        $actor = $this->requireUser();
        $payment = Payment::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $payment);
        $payment->status = 'Rejected';
        $payment->save();

        return redirect()->route('admin.payments')->with('success', 'Payment rejected successfully.');
    }

    public function deleteEvaluation($id)
    {
        $actor = $this->requireUser();
        $evaluation = Evaluation::with('threads.messages')->findOrFail($id);
        Gate::forUser($actor)->authorize('delete', $evaluation);

        DB::transaction(function () use ($evaluation) {
            foreach ($evaluation->threads as $thread) {
                $thread->messages()->delete();
                $thread->delete();
            }

            $evaluation->delete();
        });

        return redirect()->route('admin.dashboard')->with('success', 'Evaluation deleted successfully.');
    }

    protected function buildAdminActivitySeries(): array
    {
        $labels = [];
        $newUsers = [];
        $payments = [];
        $complaints = [];

        for ($offset = 5; $offset >= 0; $offset--) {
            $start = Carbon::now()->startOfMonth()->subMonths($offset);
            $end = (clone $start)->endOfMonth();

            $labels[] = $start->format('M');

            $newUsers[] = User::whereBetween('created_at', [$start->toDateTimeString(), $end->toDateTimeString()])->count();
            $payments[] = Payment::whereBetween('payment_date', [$start->toDateTimeString(), $end->toDateTimeString()])->count();
            $complaints[] = Complaint::whereBetween('resolved_at', [$start->toDateTimeString(), $end->toDateTimeString()])->count();
        }

        return [
            'labels' => $labels,
            'newUsers' => $newUsers,
            'payments' => $payments,
            'complaints' => $complaints,
        ];
    }
}
