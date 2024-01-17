<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    public function myAccount(Request $request)
    {
        $user = Auth::user();
        $user->load(['organizations']);
        $tokens = $user->tokens()->get();
        // dd($tokens);
        return view('myaccount', ['user' => $user, 'tokens' => $tokens]);
    }

    public function savePersonalInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        $user = User::findOrFail(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return Redirect::back()->with('success', 'Personal info was updated successfully.');
    }

    public function saveSenderInfo(Request $request)
    {
        $request->validate([
            'default_sender' => 'required|string|max:11',
        ]);

        $user = User::findOrFail(Auth::id());
        $organization = Organization::findOrFail($user->organization_id);
        $organization->default_sender = $request->default_sender;
        $organization->save();

        return Redirect::back()->with('success', 'Default sender info was updated successfully.');
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::findOrFail(Auth::id());

        if (!(Hash::check($request->current_password, $user->password))) {
            return Redirect::back()->withErrors(['current_password' => 'Current password does not match our records.']);
        }


        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back()->with('success', 'Password was updated successfully.');
    }

    public function myOrganization(Request $request)
    {
        $orgUsers = User::where('organization_id', Auth::user()->organization_id)
            ->withoutRole('superadmin')
            ->paginate(10);

        return view('myorganization', ['orgUsers' => $orgUsers]);
    }

    public function newOrgUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:orgadmin,default'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => Auth::user()->organization_id
        ]);

        $user->assignRole($request->role);

        return Redirect::route('myorg.index')->with('success', 'New organization user created successfully.');
    }

    public function editOrgUser(Request $request, User $user)
    {
        if (Auth::user()->organization_id != $user->organization_id) {
            abort(403);
        }

        return view('users.orgedit', ['user' => $user]);
    }

    public function updateOrgUser(Request $request, User $user)
    {
        if (Auth::user()->organization_id != $user->organization_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:orgadmin,default'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles([$user->role]);

        return Redirect::back()->with(['success' => 'Updated organization user successfully.']);
    }

    public function updateOrgUserPassword(Request $request, User $user)
    {
        if (Auth::user()->organization_id != $user->organization_id) {
            abort(403);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back()->with(['success' => 'Updated organization user password successfully.']);
    }

    public function deleteOrgUser(Request $request, User $user)
    {
        if (Auth::user()->organization_id != $user->organization_id) {
            abort(403);
        }

        if (Auth::id() != $user->id) {
            $user->delete();
            return Redirect::back()->with('success', 'Deleted organization user successfully.');
        }

        return Redirect::back();
    }

    public function createToken(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $user = Auth::user();
        $token = $user->createToken($request->name);

        return Redirect::back()->with('success', 'New token (' . $request->name . ') created successfully.');
    }

    public function revokeToken($tokenId, Request $request)
    {
        $user = Auth::user();
        $token = $user->tokens()->where('token', $tokenId)->delete();

        // dd($token);
        if($token) {
            return Redirect::back()->with('success', 'Token succesfully revoked');
        } else {
            return Redirect::back()->with('error', 'Token revoke failed.');
        }

    }

    public function myCredits(Request $request)
    {
        return view('credits.index');
    }
}
