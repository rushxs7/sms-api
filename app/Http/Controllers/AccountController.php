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
