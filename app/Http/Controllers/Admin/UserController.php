<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()
            ->paginate(15);

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make(
            $data['password']
        );

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    public function show(User $user)
    {
        $user->load([
            'reviewers.conference',
        ]);

        return view(
            'admin.users.show',
            compact('user')
        );
    }

    public function edit(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }

    public function update(
        UserRequest $request,
        User $user
    ) {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make(
                $data['password']
            );
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }

        if ($user->reviewers()->exists()) {
            return back()
                ->with(
                    'error',
                    'This user is assigned as a reviewer. Remove reviewer assignments first.'
                );
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}
