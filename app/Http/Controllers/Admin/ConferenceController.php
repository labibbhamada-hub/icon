<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ConferenceController extends Controller
{
    public function index()
    {
        return view('admin.conference.index');
    }

    public function create()
    {
        return view('admin.conference.create');
    }

    // public function store(ConferenceRequest $request)
    // {
    //     //
    // }
}
