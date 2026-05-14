<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\ReviewReportFlagRequest;
use App\Models\ReportFlag;
use Illuminate\Support\Facades\Gate;

class AdminReportFlagController extends Controller
{
    use InteractsWithSessionAuth;

    public function index()
    {
        $actor = $this->requireUser();
        Gate::forUser($actor)->authorize('viewAny', ReportFlag::class);

        $reportFlags = ReportFlag::with(['reporter', 'reviewer'])
            ->latest('report_id')
            ->get();

        return view('admin.report-flags', compact('reportFlags'));
    }

    public function review(ReviewReportFlagRequest $request, $id)
    {
        $actor = $this->requireUser();
        $reportFlag = ReportFlag::findOrFail($id);
        Gate::forUser($actor)->authorize('update', $reportFlag);

        $reportFlag->status = $request->validated()['status'];
        $reportFlag->reviewed_by = session('admin_id');
        $reportFlag->reviewed_at = now();
        $reportFlag->save();

        return redirect()->route('admin.report-flags.index')->with('success', 'Report flag reviewed successfully.');
    }
}
