<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.organization.index', ['organizations' => Organization::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        Organization::create($request->validated());

        alert()->success('Organization created successfully');
        return redirect()->route('organization.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return response()->json($organization);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        alert()->success('Organization updated successfully');
        return redirect()->route('organization.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        alert()->success('Organization deleted successfully');
        return redirect()->route('organization.index');
    }
}
