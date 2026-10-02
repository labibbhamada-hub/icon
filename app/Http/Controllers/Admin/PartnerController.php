<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRequest;
use App\Models\Conference;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

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

        return view('admin.partners.create', compact('conferences'));
    }

    public function store(PartnerRequest $request)
    {
        $data = $request->validated();

        $logoFile = null;

        try {
            if ($request->hasFile('logo')) {
                $logoFile = $request
                    ->file('logo')
                    ->store('partners', 'public');

                $data['logo'] = $logoFile;
            }

            DB::transaction(function () use ($data) {
                Partner::create($data);
            });
        } catch (Throwable $e) {
            if ($logoFile) {
                Storage::disk('public')->delete($logoFile);
            }

            throw $e;
        }

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

        $oldLogoFile = $partner->logo;
        $newLogoFile = null;

        try {
            if ($request->hasFile('logo')) {
                $newLogoFile = $request
                    ->file('logo')
                    ->store('partners', 'public');

                $data['logo'] = $newLogoFile;
            }

            DB::transaction(function () use (
                $partner,
                $data
            ) {
                $partner->update($data);
            });
        } catch (Throwable $e) {
            if ($newLogoFile) {
                Storage::disk('public')->delete($newLogoFile);
            }

            throw $e;
        }

        if (
            $newLogoFile
            && $oldLogoFile
            && $oldLogoFile !== $newLogoFile
        ) {
            Storage::disk('public')->delete($oldLogoFile);
        }

        return redirect()
            ->route('admin.partners.index')
            ->with(
                'success',
                'Partner updated successfully.'
            );
    }

    public function destroy(Partner $partner)
    {
        $logoFile = $partner->logo;

        DB::transaction(function () use ($partner) {
            $partner->delete();
        });

        if ($logoFile) {
            Storage::disk('public')->delete($logoFile);
        }

        return redirect()
            ->route('admin.partners.index')
            ->with(
                'success',
                'Partner deleted successfully.'
            );
    }
}
