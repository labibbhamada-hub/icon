<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportantDateRequest;
use App\Models\Conference;
use App\Models\ImportantDate;

class ImportantDateController extends Controller
{
    public function index()
    {
        $importantDates = ImportantDate::with('conference')->orderBy('sort_order')->orderBy('date')->paginate(10);

        return view('admin.important-dates.index', compact('importantDates'));
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view('admin.important-dates.create', compact('conferences'));
    }

    public function store(ImportantDateRequest $request)
    {
        ImportantDate::create($request->validated());

        return redirect()->route('admin.important-dates.index')->with('success', 'Important date created successfully.');
    }

    public function show(ImportantDate $importantDate)
    {
        $importantDate->load('conference');

        return view('admin.important-dates.show', compact('importantDate'));
    }

    public function edit(ImportantDate $importantDate)
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view('admin.important-dates.edit', compact('importantDate', 'conferences'));
    }

    public function update(ImportantDateRequest $request, ImportantDate $importantDate)
    {
        $importantDate->update($request->validated());

        return redirect()->route('admin.important-dates.index')->with('success', 'Important date updated successfully.');
    }

    public function destroy(ImportantDate $importantDate)
    {
        $importantDate->delete();

        return redirect()->route('admin.important-dates.index')->with('success', 'Important date deleted successfully.');
    }
}
