<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\JobPurchase;
use App\Models\Payment;
use App\Models\Report as ReportModel;
use App\Models\ReportFlag;
use App\Models\Subscription;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $reportsQuery = ReportModel::with('admin')->latest('report_id');
        $paymentsQuery = Payment::latest('payment_id');

        if ($startDate) {
            $reportsQuery->whereDate('generated_at', '>=', $startDate);
            $paymentsQuery->whereDate('payment_date', '>=', $startDate);
        }

        if ($endDate) {
            $reportsQuery->whereDate('generated_at', '<=', $endDate);
            $paymentsQuery->whereDate('payment_date', '<=', $endDate);
        }

        $reports = $reportsQuery->get();
        $payments = $paymentsQuery->get();
        $doctorComments = $this->doctorComments();

        $financialSummary = $this->financial($startDate, $endDate);
        $usageSummary = $this->usage($startDate, $endDate);

        return view('admin.reports', compact('reports', 'financialSummary', 'usageSummary', 'payments', 'startDate', 'endDate', 'doctorComments'));
    }

    public function print(int $id)
    {
        $report = ReportModel::with('admin')->findOrFail($id);
        $doctorComments = $this->doctorComments(24);
        $financialSummary = $this->financial();
        $usageSummary = $this->usage();

        return view('admin.reports-print', compact('report', 'doctorComments', 'financialSummary', 'usageSummary'));
    }

    public function printPayment(int $id)
    {
        $payment = Payment::with(['subscription', 'jobPurchase'])->findOrFail($id);

        return view('admin.payment-print', compact('payment'));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'report_type' => ['required', 'string', 'max:100'],
        ]);

        ReportModel::create([
            'generated_by' => session('admin_id'),
            'report_type' => $data['report_type'],
            'generated_at' => now(),
        ]);

        return redirect()->route('admin.reports')->with('success', 'Report generated successfully.');
    }

    public function financial(?string $startDate = null, ?string $endDate = null)
    {
        $base = Payment::query();

        if ($startDate) {
            $base->whereDate('payment_date', '>=', $startDate);
        }
        if ($endDate) {
            $base->whereDate('payment_date', '<=', $endDate);
        }

        return [
            'totalRevenue' => (clone $base)->where('status', 'Completed')->sum('amount'),
            'subscriptionRevenue' => (clone $base)->whereNotNull('subscription_id')->where('status', 'Completed')->sum('amount'),
            'purchaseRevenue' => (clone $base)->whereNotNull('purchase_id')->where('status', 'Completed')->sum('amount'),
            'completedPayments' => (clone $base)->where('status', 'Completed')->count(),
        ];
    }

    public function usage(?string $startDate = null, ?string $endDate = null)
    {
        $subscriptions = Subscription::query();
        $purchases = JobPurchase::query();
        $evaluations = Evaluation::query();
        $flags = ReportFlag::query();

        if ($startDate) {
            $subscriptions->whereDate('start_date', '>=', $startDate);
            $purchases->whereDate('purchase_date', '>=', $startDate);
            $evaluations->whereDate('created_at', '>=', $startDate);
            $flags->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $subscriptions->whereDate('start_date', '<=', $endDate);
            $purchases->whereDate('purchase_date', '<=', $endDate);
            $evaluations->whereDate('created_at', '<=', $endDate);
            $flags->whereDate('created_at', '<=', $endDate);
        }

        return [
            'subscriptions' => $subscriptions->count(),
            'purchases' => $purchases->count(),
            'evaluations' => $evaluations->count(),
            'reportFlags' => $flags->count(),
        ];
    }

    private function doctorComments(int $limit = 12)
    {
        return Evaluation::with(['user', 'subMajor.major'])
            ->where(function ($query) {
                $query->whereNotNull('experience')->where('experience', '!=', '')
                    ->orWhere(function ($nested) {
                        $nested->whereNotNull('advantages')->where('advantages', '!=', '');
                    })
                    ->orWhere(function ($nested) {
                        $nested->whereNotNull('disadvantages')->where('disadvantages', '!=', '');
                    });
            })
            ->latest('evaluation_id')
            ->limit($limit)
            ->get();
    }
}
