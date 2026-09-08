<?php

namespace App\Http\Controllers;

use App\Models\Conference;

class LandingController extends Controller
{
    public function index()
    {
        $relations = [
            'setting',
            'configuration',

            'topics' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name');
            },

            'speakers' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name');
            },

            'partners' => function ($query) {
                $query
                    ->orderBy('sort_order')
                    ->orderBy('name');
            },

            'importantDates' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('date');
            },

            'registrationTypes' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name');
            },

            'paymentMethods' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('sort_order');
            },

            'onlineMeeting',
        ];

        $conference = Conference::with($relations)
            ->whereHas('setting', function ($query) {
                $query->where('is_active', true);
            })
            ->latest('year')
            ->first();

        if (!$conference) {
            $conference = Conference::with($relations)
                ->latest('year')
                ->first();
        }

        return view(
            'landing.index',
            compact('conference')
        );
    }
}
