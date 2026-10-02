<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewerRequest;
use App\Models\Conference;
use App\Models\Reviewer;
use App\Models\User;

class ReviewerController extends Controller
{
    public function index()
    {
        $reviewers = Reviewer::with([
            'conference',
            'user',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.reviewers.index',
            compact('reviewers')
        );
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        $users = User::where('role', 'reviewer')
            ->orderBy('name')
            ->get();

        return view(
            'admin.reviewers.create',
            compact(
                'conferences',
                'users'
            )
        );
    }

    public function store(ReviewerRequest $request)
    {
        Reviewer::create(
            $request->validated()
        );

        return redirect()
            ->route('admin.reviewers.index')
            ->with(
                'success',
                'Reviewer added successfully.'
            );
    }

    public function show(Reviewer $reviewer)
    {
        $reviewer->load([
            'conference',
            'user',
            'reviews.submission',
        ]);

        return view('admin.reviewers.show', compact('reviewer'));
    }

    public function edit(Reviewer $reviewer)
    {
        $conferences = Conference::orderByDesc('year')
            ->get();

        $users = User::where('role', 'reviewer')
            ->orderBy('name')
            ->get();

        return view(
            'admin.reviewers.edit',
            compact(
                'reviewer',
                'conferences',
                'users'
            )
        );
    }

    public function update(
        ReviewerRequest $request,
        Reviewer $reviewer
    ) {
        $data = $request->validated();

        if (
            (int) $reviewer->conference_id !==
            (int) $data['conference_id']
            && $reviewer->reviews()->exists()
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Reviewer cannot be moved to another conference because review records already exist.'
                );
        }

        $reviewer->update($data);

        return redirect()
            ->route('admin.reviewers.index')
            ->with(
                'success',
                'Reviewer updated successfully.'
            );
    }

    public function destroy(Reviewer $reviewer)
    {
        if ($reviewer->reviews()->exists()) {
            return back()
                ->with(
                    'error',
                    'Reviewer cannot be deleted because review records already exist.'
                );
        }

        $reviewer->delete();

        return redirect()
            ->route('admin.reviewers.index')
            ->with(
                'success',
                'Reviewer deleted successfully.'
            );
    }
}
