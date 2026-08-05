<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
