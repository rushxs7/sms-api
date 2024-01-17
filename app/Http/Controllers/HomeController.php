<?php

namespace App\Http\Controllers;

use App\Models\SendJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $orgUserCollection = User::where('organization_id', Auth::user()->organization_id)->get()->pluck('id')->toArray();
        $unreadNotifications = $user->unreadNotifications;
        $jobs = SendJob::whereIn('user_id', $orgUserCollection)
            ->with(['messages'])
            ->latest()
            ->take(5)
            ->get();
        return view('dashboard',
        [
            'jobs' => $jobs,
            'unreadNotifications' => $unreadNotifications
        ]);
    }
}
