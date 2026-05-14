<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\ComplaintStoreRequest;
use App\Models\Complaint as ComplaintModel;
use Illuminate\Support\Facades\Gate;

class ComplaintController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', ComplaintModel::class);

        $complaints = ComplaintModel::with(['user', 'admin', 'evaluation.user', 'evaluation.role.subMajor.major'])
            ->latest('complaint_id')
            ->get();

        return view('admin.complaints', compact('complaints'));
    }

    public function show($id)
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', ComplaintModel::class);

        $complaints = ComplaintModel::with(['user', 'admin', 'evaluation.user', 'evaluation.role.subMajor.major'])
            ->latest('complaint_id')
            ->get();

        $complaint = ComplaintModel::with(['user', 'admin', 'evaluation.user', 'evaluation.role.subMajor.major'])->findOrFail($id);

        return view('admin.complaints', compact('complaints', 'complaint'));
    }

    public function resolve($id)
    {
        $actor = $this->requireUser();
        $complaint = ComplaintModel::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $complaint);
        $complaint->status = 'Resolved';
        $complaint->resolved_by = session('admin_id');
        $complaint->resolved_at = now();
        $complaint->save();

        return redirect()->route('admin.complaints')->with('success', 'Complaint resolved successfully.');
    }

    public function store(ComplaintStoreRequest $request)
    {
        $user = $this->currentUser();
        $data = $request->validated();

        ComplaintModel::create([
            'user_id' => $user->user_id,
            'evaluation_id' => $data['evaluation_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'Pending',
            'resolved_by' => null,
            'resolved_at' => null,
        ]);

        return redirect()->route('complaints.index')->with('success', 'Complaint submitted successfully.');
    }

    public function myIndex()
    {
        $user = $this->currentUser();

        $complaints = ComplaintModel::with(['evaluation.role.subMajor.major', 'evaluation.user'])
            ->where('user_id', $user->user_id)
            ->latest('complaint_id')
            ->get();

        return view('complaints.index', compact('complaints'));
    }

    public function update(ComplaintStoreRequest $request, $id)
    {
        $user = $this->currentUser();
        $complaint = ComplaintModel::where('user_id', $user->user_id)->findOrFail($id);

        if (strtolower((string) $complaint->status) === 'resolved') {
            return redirect()->route('complaints.index')->with('error', 'Resolved complaints cannot be edited.');
        }

        $data = $request->validated();

        $complaint->title = $data['title'];
        $complaint->description = $data['description'];
        $complaint->save();

        return redirect()->route('complaints.index')->with('success', 'Complaint updated successfully.');
    }
}
