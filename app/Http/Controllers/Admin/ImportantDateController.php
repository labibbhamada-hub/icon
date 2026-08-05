<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ImportantDateController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
