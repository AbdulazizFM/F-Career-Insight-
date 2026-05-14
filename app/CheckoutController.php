<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\JobPurchase;
use App\Models\Payment;
use App\Models\SubMajor;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use InteractsWithSessionAuth;

    public function start(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'type' => ['required', 'in:monthly,single'],
            'sub_major_id' => ['nullable', 'exists:SUBMAJOR,sub_major_id'],
        ]);

        $checkout = [
            'type' => $data['type'],
            'amount' => $data['type'] === 'monthly' ? 29 : 10,
            'label' => $data['type'] === 'monthly' ? 'Full Monthly Access' : 'Single Job Access',
            'sub_major' => null,
        ];

        if ($data['type'] === 'single') {
            $subMajor = SubMajor::with('major')->findOrFail($data['sub_major_id']);
            $alreadyPurchased = JobPurchase::where('user_id', $user->user_id)->where('sub_major_id', $subMajor->sub_major_id)->exists();
            if ($alreadyPurchased) {
                return redirect()->route('jobs.show', $subMajor->sub_major_id)->with('error', 'This role has already been purchased.');
            }
            $checkout['sub_major'] = $subMajor;
        } else {
            $activeSubscription = Subscription::where('user_id', $user->user_id)
                ->activeNow()
                ->latest('subscription_id')
                ->first();
            if ($activeSubscription) {
                return redirect()->route('subscriptions.index')->with('error', 'You already have an active subscription.');
            }
        }

        return view('checkout.index', compact('checkout'));
    }

    public function process(Request $request)
    {
        $user = $this->currentUser();

        $data = $request->validate([
            'type' => ['required', 'in:monthly,single'],
            'sub_major_id' => ['nullable', 'exists:SUBMAJOR,sub_major_id'],
            'result' => ['required', 'in:success,fail'],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        if ($data['result'] === 'fail') {
            return redirect()->route('checkout.fail')->with('error', 'Payment failed. Please try again.');
        }

        if ($data['type'] === 'monthly') {
            DB::transaction(function () use ($user, $data) {
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
                    'payment_method' => $data['payment_method'],
                    'payment_date' => now(),
                    'status' => 'Completed',
                ]);
            });
        } else {
            $subMajor = SubMajor::findOrFail($data['sub_major_id']);
            DB::transaction(function () use ($user, $subMajor, $data) {
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
                    'payment_method' => $data['payment_method'],
                    'payment_date' => now(),
                    'status' => 'Completed',
                ]);
            });
        }

        return redirect()->route('checkout.success')->with('success', 'Payment completed successfully.');
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function fail()
    {
        return view('checkout.fail');
    }
}
