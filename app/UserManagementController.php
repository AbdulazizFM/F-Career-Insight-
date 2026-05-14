<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserUpdateRequest;
use App\Models\Admin;
use App\Models\Complaint;
use App\Models\Evaluation;
use App\Models\JobPurchase;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\Payment;
use App\Models\ReportFlag;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $actor = User::findOrFail((int) session('user_id'));
        Gate::forUser($actor)->authorize('viewAny', User::class);

        $users = User::withCount(['evaluations', 'jobPurchases', 'messages', 'complaints'])
            ->with([
                'subscription.payment',
                'evaluations.subMajor.major',
                'jobPurchases.subMajor.major',
                'complaints',
            ])
            ->latest('user_id')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function show($id)
    {
        $actor = User::findOrFail((int) session('user_id'));
        Gate::forUser($actor)->authorize('viewAny', User::class);

        $users = User::withCount(['evaluations', 'jobPurchases'])
            ->with('subscription')
            ->latest('user_id')
            ->get();

        $user = User::with(['evaluations.subMajor.major', 'jobPurchases.subMajor.major', 'subscription'])
            ->findOrFail($id);

        return view('admin.users', compact('users', 'user'));
    }

    public function update(AdminUserUpdateRequest $request, $id)
    {
        $actor = User::findOrFail((int) session('user_id'));
        $user = User::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $user);
        $data = $request->validated();

        $user->full_name = $data['full_name'];
        $user->email = $data['email'];
        $user->account_status = $data['account_status'];
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User account updated successfully.');
    }

    public function suspend($id)
    {
        $actor = User::findOrFail((int) session('user_id'));
        $user = User::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $user);
        $user->account_status = 'Suspended';
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User suspended successfully.');
    }

    public function activate($id)
    {
        $actor = User::findOrFail((int) session('user_id'));
        $user = User::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $user);
        $user->account_status = 'Active';
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User activated successfully.');
    }

    public function destroy($id)
    {
        $actor = User::findOrFail((int) session('user_id'));
        $user = User::findOrFail($id);
        Gate::forUser($actor)->authorize('delete', $user);

        if (Admin::where('user_id', $user->user_id)->exists()) {
            return redirect()->route('admin.users')->with('error', 'Admin accounts cannot be deleted from user management.');
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
            Message::where('sender_id', $user->user_id)->delete();
            $user->delete();
        });

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
