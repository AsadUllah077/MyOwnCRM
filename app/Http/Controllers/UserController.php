<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::latest()->get()]);
    }

    public function create()
    {
        $roles = Role::get();
        return view('users.create', ['roles' => $roles]);
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return view('users.edit', compact('user', 'roles', 'userRoleIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles'    => 'array'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->roles);
        // Display a success toast with no title
        toastr()->success('User created successfully.');
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles'    => 'array'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        $user->roles()->sync($request->roles ?? []);
        toastr()->success('User updated successfully.');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            toastr()->error('You can not delete yourself');
            return back();
        }

        $user->roles()->detach();
        $user->delete();
        toastr()->success('User deleted successfully.');
        return redirect()->route('users.index');
    }
}
