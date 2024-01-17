<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('id', '!=', Auth::id())
            ->with(['roles'])
            ->latest()
            ->withTrashed()
            ->paginate(10);

        $organizations = Organization::all();

        return view('users.index', [
            'users' => $users,
            'organizations' => $organizations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'organization_id' => 'required|integer',
        ]);

        $datasurOrg = Organization::where('name', 'Datasur')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $datasurOrg->id,
        ]);

        $user->assignRole($request->role);

        return Redirect::route('users.index')->with('success', 'New user account created succesfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Request $request)
    {
        $user->load(['tokens', 'organizations']);
        $tokens = $user->tokens()->get();
        $organizations = Organization::all();
        return view('users.edit', [
            'user' => $user,
            'tokens' => $tokens,
            'organizations' => $organizations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'organization_id' => 'required|integer'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->organization_id = $request->organization_id;
        $user->save();

        return Redirect::route('users.edit', ['user' => $user])->with('success', 'User account updated succesfully.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::route('users.edit', ['user' => $user])->with('success', 'User account password updated succesfully.');
    }

    public function updateDefaultSender(Request $request, User $user)
    {
        $request->validate([
            'default_sender' => 'nullable|string|max:11'
        ]);

        $organization = $user->organizations;
        $organization->default_sender = $request->default_sender;
        $organization->save();

        return Redirect::route('users.edit', ['user' => $user])->with('success', 'SMS sender display name updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return Redirect::route('users.index')->with('success', 'User account disabled succesfully.');
    }

    public function enable(int $user)
    {
        $user = User::withTrashed()->where('id', $user)->first();
        $user->restore();
        return Redirect::route('users.index')->with('success', 'User account enabled succesfully.');
    }
}
