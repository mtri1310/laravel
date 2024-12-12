<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    //
    public function index(): View
    {
        return view('users.index', [
            'users' => User::latest()->paginate(3)
        ]);
    }

    public function create(): View
    {
        //
        return view('users.create');
    }

    public function store(StoreUserRequest $request) : RedirectResponse
    {
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('profiles', 'public');
        }
        User::create($request->all());
        session()->flash('messageSuccess', 'New user is added successfully.');
        return redirect()->route('users.index');
    }

    public function show(User $user) : View
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    public function edit(User $user) : View
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user) : RedirectResponse
    {
        $user->update($request->all());
        return redirect()->back()
                ->withSuccess('User is updated successfully.');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(User $user) : RedirectResponse
    {
        $user->delete();
        return redirect()->route('users.index')
                ->withSuccess('User is deleted successfully.');
    }
}
