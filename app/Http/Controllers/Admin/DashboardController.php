<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Topic;

class DashboardController extends Controller
{
    public function index()
    {
        $conferenceCount = Conference::count();

        $topicCount = Topic::count();

        $activeConference = Conference::with('setting')
            ->whereHas('setting', function ($query) {
                $query->where('is_active', true);
            })
            ->latest('year')
            ->first();

        if (!$activeConference) {
            $activeConference = Conference::latest('year')->first();
        }

        $latestTopics = Topic::with('conference')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'conferenceCount',
            'topicCount',
            'activeConference',
            'latestTopics'
        ));
    }
}
