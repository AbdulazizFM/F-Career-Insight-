<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Http\Requests\EvaluationRequest;
use App\Models\Evaluation;
use App\Models\MessageThread;
use App\Models\Role;
use App\Models\SubMajor;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    use InteractsWithSessionAuth;

    public function create($subMajorId = null)
    {
        $user = $this->currentUser();
        $subMajors = SubMajor::with('major')->orderBy('sub_major_name')->get();
        $roles = Role::with('subMajor.major')->orderBy('role_name')->get();
        $selectedSubMajor = $subMajorId ? SubMajor::with('major')->find($subMajorId) : null;
        $existingEvaluation = $selectedSubMajor
            ? Evaluation::where('user_id', $user->user_id)->where('sub_major_id', $selectedSubMajor->sub_major_id)->first()
            : null;

        return view('evaluations.create', compact('subMajors', 'roles', 'user', 'selectedSubMajor', 'existingEvaluation'));
    }

    public function index()
    {
        $user = $this->currentUser();

        $evaluations = Evaluation::with(['subMajor.major', 'role.subMajor.major', 'threads.messages'])
            ->where('user_id', $user->user_id)
            ->latest('evaluation_id')
            ->paginate(10);

        $subMajors = SubMajor::with('major')->orderBy('sub_major_name')->get();
        $roles = Role::with('subMajor.major')->orderBy('role_name')->get();

        return view('evaluations.index', compact('evaluations', 'subMajors', 'roles', 'user'));
    }

    public function store(EvaluationRequest $request)
    {
        $user = $this->currentUser();

        $data = $request->validated();
        $role = null;

        if (!empty($data['role_id'])) {
            $role = Role::findOrFail($data['role_id']);
            $data['sub_major_id'] = $role->sub_major_id;
        } elseif (!empty($data['sub_major_id'])) {
            $role = Role::where('sub_major_id', $data['sub_major_id'])->orderBy('role_id')->first();
            $data['role_id'] = optional($role)->role_id;
        }

        $existingEvaluation = Evaluation::where('user_id', $user->user_id)
            ->where('role_id', $data['role_id'])
            ->first();

        if ($existingEvaluation) {
            return redirect()
                ->route('evaluations.index')
                ->with('error', 'You already submitted an evaluation for this job title.');
        }

        DB::transaction(function () use ($data, $user) {
            $evaluation = Evaluation::create([
                'user_id' => $user->user_id,
                'sub_major_id' => $data['sub_major_id'],
                'role_id' => $data['role_id'],
                'rating' => $data['rating'],
                'advantages' => $data['advantages'],
                'disadvantages' => $data['disadvantages'],
                'experience' => $data['experience'],
            ]);

            MessageThread::firstOrCreate([
                'evaluation_id' => $evaluation->evaluation_id,
            ], [
                'created_at' => now(),
            ]);
        });

        return redirect()->route('evaluations.index')->with('success', 'Evaluation created successfully.');
    }

    public function edit($id)
    {
        $user = $this->currentUser();
        $evaluation = Evaluation::with('role.subMajor.major')->where('user_id', $user->user_id)->findOrFail($id);
        $subMajors = SubMajor::with('major')->orderBy('sub_major_name')->get();
        $roles = Role::with('subMajor.major')->orderBy('role_name')->get();

        return view('evaluations.create', compact('evaluation', 'subMajors', 'roles', 'user'));
    }

    public function update(EvaluationRequest $request, $id)
    {
        $user = $this->currentUser();
        $evaluation = Evaluation::where('user_id', $user->user_id)->findOrFail($id);

        $data = $request->validated();
        if (!empty($data['role_id'])) {
            $role = Role::findOrFail($data['role_id']);
            $data['sub_major_id'] = $role->sub_major_id;
        } elseif (!empty($data['sub_major_id'])) {
            $role = Role::where('sub_major_id', $data['sub_major_id'])->orderBy('role_id')->first();
            $data['role_id'] = optional($role)->role_id;
        }

        $duplicateExists = Evaluation::where('user_id', $user->user_id)
            ->where('role_id', $data['role_id'])
            ->where('evaluation_id', '!=', $evaluation->evaluation_id)
            ->exists();

        if ($duplicateExists) {
            return back()->withInput()->withErrors([
                'role_id' => 'You already have an evaluation for this role.',
            ]);
        }

        $evaluation->update($data);

        return redirect()->route('evaluations.index')->with('success', 'Evaluation updated successfully.');
    }

    public function destroy($id)
    {
        $user = $this->currentUser();
        $evaluation = Evaluation::where('user_id', $user->user_id)->findOrFail($id);

        DB::transaction(function () use ($evaluation) {
            foreach ($evaluation->threads as $thread) {
                $thread->messages()->delete();
                $thread->delete();
            }

            $evaluation->delete();
        });

        return redirect()->route('evaluations.index')->with('success', 'Evaluation deleted successfully.');
    }
}
