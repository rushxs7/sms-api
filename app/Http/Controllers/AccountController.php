<?php

namespace App\Http\Controllers;

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
        return view('myaccount', ['user' => $user]);
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

    public function passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::findOrFail(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back()->with('success', 'Password was updated successfully.');
    }

    public function myFinances(Request $request)
    {
        return view('myfinances');
    }
}
