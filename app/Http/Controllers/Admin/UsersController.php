<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function creaete()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());
        flash()->success('User Created.');

        return back();
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        flash()->success('User Updated.');

        return back();
    }

    public function destroy(User $user)
    {
        $user->delete();
        flash()->success('User Deleted.');

        return back();
    }
}
