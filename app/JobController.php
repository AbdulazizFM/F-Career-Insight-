<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithSessionAuth;
use App\Models\Evaluation;
use App\Models\JobPurchase;
use App\Models\Major;
use App\Models\SubMajor;
use App\Models\Subscription;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use InteractsWithSessionAuth;

    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $majorFilter = $request->get('major_id');
        $salaryFilter = $request->get('salary_range');

        $query = SubMajor::with(['major', 'roles'])
            ->withCount('evaluations')
            ->withAvg('evaluations as average_rating', 'rating');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('sub_major_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('major', function ($majorQuery) use ($search) {
                        $majorQuery->where('major_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($majorFilter) {
            $query->where('major_id', $majorFilter);
        }

        if ($salaryFilter) {
            $salaryMinExpr = "CAST(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(COALESCE(salary_range, ''), '-', 1), 'SAR', 1), ',', '') AS UNSIGNED)";
            $salaryMaxExpr = "CAST(REPLACE(SUBSTRING_INDEX(COALESCE(salary_range, ''), '-', -1), ',', '') AS UNSIGNED)";

            if ($salaryFilter === 'under_10000') {
                $query->whereHas('roles', function ($roleQuery) use ($salaryMinExpr) {
                    $roleQuery->whereRaw($salaryMinExpr . ' < ?', [10000]);
                });
            } elseif ($salaryFilter === '10000_20000') {
                $query->whereHas('roles', function ($roleQuery) use ($salaryMinExpr) {
                    $roleQuery->whereRaw($salaryMinExpr . ' BETWEEN ? AND ?', [10000, 20000]);
                });
            } elseif ($salaryFilter === '20000_plus') {
                $query->whereHas('roles', function ($roleQuery) use ($salaryMaxExpr) {
                    $roleQuery->whereRaw($salaryMaxExpr . ' >= ?', [20000]);
                });
            }
        }

        $subMajors = $query->orderBy('sub_major_name')->paginate(9)->appends($request->only(['q', 'major_id', 'salary_range']));

        $majors = Major::orderBy('major_name')->get();
        $salaryRanges = [
            ['value' => '', 'label' => 'All salary ranges'],
            ['value' => 'under_10000', 'label' => 'Under 10,000 SAR'],
            ['value' => '10000_20000', 'label' => '10,000 - 20,000 SAR'],
            ['value' => '20000_plus', 'label' => '20,000 SAR+'],
        ];

        return view('jobs.index', compact('subMajors', 'majors', 'search', 'majorFilter', 'salaryFilter', 'salaryRanges'));
    }

    public function show($id)
    {
        $subMajor = SubMajor::with(['major', 'roles'])->findOrFail($id);

        $evaluations = Evaluation::with('user')
            ->where('sub_major_id', $subMajor->sub_major_id)
            ->latest('evaluation_id')
            ->paginate(6);

        $averageRating = round((float) Evaluation::where('sub_major_id', $subMajor->sub_major_id)->avg('rating'), 1);
        $evaluationCount = Evaluation::where('sub_major_id', $subMajor->sub_major_id)->count();

        $similarJobs = SubMajor::with('major')
            ->where('major_id', $subMajor->major_id)
            ->where('sub_major_id', '!=', $subMajor->sub_major_id)
            ->take(4)
            ->get();

        $user = $this->currentUser();
        $myEvaluation = $user
            ? Evaluation::where('user_id', $user->user_id)
                ->where('sub_major_id', $subMajor->sub_major_id)
                ->latest('evaluation_id')
                ->first()
            : null;
        $currentSubscription = $user
            ? Subscription::where('user_id', $user->user_id)
                ->activeNow()
                ->latest('subscription_id')
                ->first()
            : null;

        $purchased = $user
            ? JobPurchase::where('user_id', $user->user_id)
                ->where('sub_major_id', $subMajor->sub_major_id)
                ->exists()
            : false;

        $accessGranted = (bool) $currentSubscription || $purchased;

        if (! $accessGranted) {
            $evaluations = collect();
        }

        $primaryRole = $subMajor->roles->first();
        $effectiveSalaryRange = optional($primaryRole)->salary_range;
        $subMajor->challenges = optional($primaryRole)->challenges;

        [$salaryMin, $salaryMax] = $this->parseSalaryRange($effectiveSalaryRange);
        $salaryRangeLabel = $this->buildSalaryLabel($salaryMin, $salaryMax, $effectiveSalaryRange);
        $salaryMeterClass = $this->salaryMeterClass($salaryMin, $salaryMax);

        return view('jobs.show', compact(
            'subMajor',
            'evaluations',
            'averageRating',
            'evaluationCount',
            'similarJobs',
            'currentSubscription',
            'purchased',
            'accessGranted',
            'myEvaluation',
            'salaryMin',
            'salaryMax',
            'salaryRangeLabel',
            'salaryMeterClass'
        ));
    }

    protected function parseSalaryRange(?string $salaryRange): array
    {
        $clean = (string) $salaryRange;

        if ($clean === '') {
            return [null, null];
        }

        preg_match_all('/\d[\d,]*/', $clean, $matches);
        $values = array_map(static function ($value) {
            return (int) str_replace(',', '', $value);
        }, $matches[0] ?? []);

        if (empty($values)) {
            return [null, null];
        }

        $min = $values[0];
        $max = $values[1] ?? $values[0];

        return [$min, $max];
    }

    protected function buildSalaryLabel(?int $salaryMin, ?int $salaryMax, ?string $fallback = null): string
    {
        if ($salaryMin && $salaryMax) {
            return number_format($salaryMin) . ' - ' . number_format($salaryMax) . ' SAR';
        }

        return $fallback ?: 'Price on access';
    }

    protected function salaryMeterClass(?int $salaryMin, ?int $salaryMax): string
    {
        if (! $salaryMin || ! $salaryMax) {
            return 'salary-fill-70';
        }

        $ratio = $salaryMax > 0 ? ($salaryMin / $salaryMax) : 0;

        if ($ratio <= 0.35) {
            return 'salary-fill-35';
        }

        if ($ratio <= 0.45) {
            return 'salary-fill-45';
        }

        if ($ratio <= 0.55) {
            return 'salary-fill-55';
        }

        if ($ratio <= 0.65) {
            return 'salary-fill-65';
        }

        if ($ratio <= 0.75) {
            return 'salary-fill-75';
        }

        if ($ratio <= 0.85) {
            return 'salary-fill-85';
        }

        return 'salary-fill-92';
    }
}
