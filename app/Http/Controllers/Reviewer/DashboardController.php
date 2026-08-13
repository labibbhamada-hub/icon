<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $reviewer = Auth::user()
            ->reviewers()
            ->where('is_active', true)
            ->latest()
            ->first();

        $reviewCount = 0;
        $pendingCount = 0;
        $completedCount = 0;
        $recentReviews = collect();

        if ($reviewer) {
            $reviews = Review::with([
                'submission.conference',
                'submission.topic',
            ])
                ->where('reviewer_id', $reviewer->id)
                ->latest()
                ->get();

            $reviewCount = $reviews->count();

            $pendingCount = $reviews
                ->whereNull('reviewed_at')
                ->count();

            $completedCount = $reviews
                ->whereNotNull('reviewed_at')
                ->count();

            $recentReviews = $reviews
                ->take(5);
        }

        return view('reviewer.dashboard', compact('reviewer', 'reviewCount', 'pendingCount', 'completedCount', 'recentReviews'));
    }
}
