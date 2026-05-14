<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\JobPurchase;
use App\Models\Payment;
use App\Models\SubMajor;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $user = $this->currentUser();

        $currentSubscription = Subscription::with('payment')
            ->where('user_id', $user->user_id)
            ->latest('subscription_id')
            ->first();

        $recentPurchases = JobPurchase::with(['subMajor.major', 'payment'])
            ->where('user_id', $user->user_id)
            ->latest('purchase_id')
            ->get();

        $availableJobs = SubMajor::with('major')
            ->withCount('evaluations')
            ->withAvg('evaluations as average_rating', 'rating')
            ->orderBy('sub_major_name')
            ->get();

        return view('subscriptions.index', compact('currentSubscription', 'recentPurchases', 'availableJobs', 'user'));
    }

    public function subscribeMonthly()
    {
        $user = $this->currentUser();

        $activeSubscription = Subscription::where('user_id', $user->user_id)
            ->activeNow()
            ->latest('subscription_id')
            ->first();

        if ($activeSubscription) {
            return redirect()->route('subscriptions.index')->with('error', 'You already have an active subscription.');
        }

        DB::transaction(function () use ($user) {
            $subscription = Subscription::create([
                'user_id' => $user->user_id,
                'plan_type' => 'Monthly',
                'price' => 29,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'status' => 'Active',
            ]);

            Payment::create([
                'subscription_id' => $subscription->subscription_id,
                'purchase_id' => null,
                'amount' => 29,
                'transaction_id' => 'SUB-' . $subscription->subscription_id . '-' . now()->timestamp,
                'payment_method' => 'Manual',
                'payment_date' => now(),
                'status' => 'Completed',
            ]);
        });

        return redirect()->route('subscriptions.index')->with('success', 'Monthly subscription activated.');
    }

    public function buySingleJob($subMajorId)
    {
        $user = $this->currentUser();
        $subMajor = SubMajor::findOrFail($subMajorId);

        $existingPurchase = JobPurchase::where('user_id', $user->user_id)
            ->where('sub_major_id', $subMajor->sub_major_id)
            ->first();

        if ($existingPurchase) {
            return redirect()->route('jobs.show', $subMajor->sub_major_id)->with('error', 'This role has already been purchased.');
        }

        DB::transaction(function () use ($user, $subMajor) {
            $purchase = JobPurchase::create([
                'user_id' => $user->user_id,
                'sub_major_id' => $subMajor->sub_major_id,
                'price' => 10,
                'purchase_date' => now(),
            ]);

            Payment::create([
                'subscription_id' => null,
                'purchase_id' => $purchase->purchase_id,
                'amount' => 10,
                'transaction_id' => 'JOB-' . $purchase->purchase_id . '-' . now()->timestamp,
                'payment_method' => 'Manual',
                'payment_date' => now(),
                'status' => 'Completed',
            ]);
        });

        return redirect()->route('jobs.show', $subMajor->sub_major_id)->with('success', 'Single job access purchased successfully.');
    }

    public function myPurchases()
    {
        return $this->index();
    }
}
