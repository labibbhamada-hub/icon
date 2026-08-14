<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $participants = Participant::with([
            'conference',
            'submissions.topic',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('participant.dashboard', compact('participants'));
    }
}
