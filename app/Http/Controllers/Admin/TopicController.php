<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Models\Conference;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::with('conference')->latest()->paginate(10);

        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view('admin.topics.create', compact('conferences'));
    }

    public function store(TopicRequest $request)
    {
        $data = $request->validated();

        Topic::create($data);

        return redirect()
            ->route('admin.topics.index')
            ->with(
                'success',
                'Topic created successfully.'
            );
    }

    public function show(Topic $topic)
    {
        return view('admin.topics.show', compact('topic'));
    }

    public function edit(Topic $topic)
    {
        $conferences = Conference::orderByDesc('year')->get();

        return view('admin.topics.edit', compact('topic', 'conferences'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update(
            $request->validated()
        );

        return redirect()
            ->route('admin.topics.index')
            ->with(
                'success',
                'Topic updated successfully.'
            );
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()
            ->route('admin.topics.index')
            ->with(
                'success',
                'Topic deleted successfully.'
            );
    }
}
