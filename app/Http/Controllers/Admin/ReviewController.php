<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index');
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store()
    {
        //
    }

    public function show(Review $review)
    {
        return view(
            'admin.reviews.show',
            compact('review')
        );
    }

    public function edit(Review $review)
    {
        return view(
            'admin.reviews.edit',
            compact('review')
        );
    }

    public function update()
    {
        //
    }

    public function destroy(Review $review)
    {
        //
    }
}
