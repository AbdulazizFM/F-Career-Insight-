<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Major;
use App\Models\SubMajor;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $featuredMajors = Major::withCount('subMajors')
            ->orderByDesc('sub_majors_count')
            ->take(4)
            ->get();

        $featuredSubMajors = SubMajor::with(['major'])
            ->withCount('evaluations')
            ->withAvg('evaluations as average_rating', 'rating')
            ->orderByDesc('sub_major_id')
            ->take(6)
            ->get();

        $searchResults = collect();
        if ($search !== '') {
            $searchResults = SubMajor::with('major')
                ->where('sub_major_name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('major', function ($query) use ($search) {
                    $query->where('major_name', 'like', '%' . $search . '%');
                })
                ->withCount('evaluations')
                ->withAvg('evaluations as average_rating', 'rating')
                ->orderBy('sub_major_name')
                ->paginate(8)
                ->appends($request->only('q'));
        }

        $counts = [
            'totalJobTitles' => SubMajor::count(),
            'totalEvaluations' => Evaluation::count(),
            'activeUsers' => User::whereRaw('LOWER(account_status) = ?', ['active'])->count(),
        ];

        $pricing = [
            [
                'label' => 'Single Job Title Access',
                'price' => 10,
                'suffix' => 'SAR',
                'description' => 'Unlock one job title and its evaluation details.',
            ],
            [
                'label' => 'Full Monthly Access',
                'price' => 29,
                'suffix' => 'SAR',
                'description' => 'Access all job titles and messaging for one month.',
            ],
        ];

        return view('home', compact(
            'search',
            'featuredMajors',
            'featuredSubMajors',
            'searchResults',
            'counts',
            'pricing'
        ));
    }
}
