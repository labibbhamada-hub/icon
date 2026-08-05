<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SpeakerController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
