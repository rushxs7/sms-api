<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = Organization::withTrashed()->withCount(['users'])->paginate(10);

        return view('organizations.index', ['organizations' => $organizations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'default_sender' => 'nullable|string|max:11',
        ]);

        $user = Organization::create([
            'name' => $request->name,
            'default_sender' => $request->default_sender ? $request->default_sender : null,
        ]);

        return Redirect::route('organizations.index')->with('success', 'New organization created succesfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        $organization->load(['users']);

        return view('organizations.edit', ['organization' => $organization]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'required',
            'default_sender' => 'nullable|string|max:11',
        ]);

        $organization->name = $request->name;
        $organization->default_sender = $request->default_sender;
        $organization->save();

        return Redirect::route('organizations.edit', ['organization' => $organization])->with('success', 'Organization updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return Redirect::route('organizations.index')->with('success', 'Organization archived succesfully.');
    }
}
