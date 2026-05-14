<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\ProfileDeleteRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Complaint;
use App\Models\Evaluation;
use App\Models\JobPurchase;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\Payment;
use App\Models\ReportFlag;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $user = $this->currentUser();

        $currentSubscription = Subscription::where('user_id', $user->user_id)
            ->latest('subscription_id')
            ->first();

        $purchaseHistory = JobPurchase::with(['subMajor.major', 'payment'])
            ->where('user_id', $user->user_id)
            ->latest('purchase_id')
            ->get();

        $evaluationHistory = Evaluation::with(['subMajor.major'])
            ->where('user_id', $user->user_id)
            ->latest('evaluation_id')
            ->get();

        $complaints = Complaint::where('user_id', $user->user_id)->latest('complaint_id')->get();

        return view('profile.index', compact(
            'user',
            'currentSubscription',
            'purchaseHistory',
            'evaluationHistory',
            'complaints'
        ));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = $this->currentUser();

        $data = $request->validated();

        $user->full_name = $data['full_name'];
        $user->email = $data['email'];

        $user->job_title = $data['job_title'] ?? null;
        $user->company = $data['company'] ?? null;
        $user->years_experience = $data['years_experience'] ?? null;

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        session()->put([
            'user_name' => $user->full_name,
            'user_email' => $user->email,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function show()
    {
        return $this->index();
    }

    public function destroy(ProfileDeleteRequest $request)
    {
        $user = $this->currentUser();

        $data = $request->validated();

        if (! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Password confirmation failed.']);
        }

        DB::transaction(function () use ($user) {
            $evaluationIds = Evaluation::where('user_id', $user->user_id)->pluck('evaluation_id')->all();
            $threadIds = MessageThread::whereIn('evaluation_id', $evaluationIds)->pluck('thread_id')->all();

            if (! empty($threadIds)) {
                Message::whereIn('thread_id', $threadIds)->delete();
                MessageThread::whereIn('thread_id', $threadIds)->delete();
            }

            Payment::whereIn('subscription_id', Subscription::where('user_id', $user->user_id)->pluck('subscription_id'))->delete();
            Payment::whereIn('purchase_id', JobPurchase::where('user_id', $user->user_id)->pluck('purchase_id'))->delete();

            Subscription::where('user_id', $user->user_id)->delete();
            JobPurchase::where('user_id', $user->user_id)->delete();
            Evaluation::where('user_id', $user->user_id)->delete();
            Complaint::where('user_id', $user->user_id)->delete();
            ReportFlag::where('reporter_id', $user->user_id)->delete();
            $user->delete();
        });

        session()->forget(['user_id', 'user_name', 'user_email', 'user_type', 'admin_id', 'admin_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Account deleted successfully.');
    }
}
