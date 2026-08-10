<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Conference;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::with('conference')
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        return view(
            'admin.partners.create',
            compact('conferences')
        );
    }

    public function store(PartnerRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request
                ->file('logo')
                ->store('partners', 'public');
        }

        Partner::create($data);

        return redirect()
            ->route('admin.partners.index')
            ->with(
                'success',
                'Partner created successfully.'
            );
    }

    public function show(Partner $partner)
    {
        $partner->load('conference');

        return view(
            'admin.partners.show',
            compact('partner')
        );
    }

    public function edit(Partner $partner)
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        return view(
            'admin.partners.edit',
            compact(
                'partner',
                'conferences'
            )
        );
    }

    public function update(
        PartnerRequest $request,
        Partner $partner
    ) {
        $data = $request->validated();

        if ($request->hasFile('logo')) {

            if ($partner->logo) {
                Storage::disk('public')
                    ->delete($partner->logo);
            }

            $data['logo'] = $request
                ->file('logo')
                ->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()
            ->route('admin.partners.index')
            ->with(
                'success',
                'Partner updated successfully.'
            );
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')
                ->delete($partner->logo);
        }

        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with(
                'success',
                'Partner deleted successfully.'
            );
    }
}
