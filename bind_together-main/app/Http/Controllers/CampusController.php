<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampusRequest;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.campus.index', ['campuses' => Campus::all()]);
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
    public function store(StoreCampusRequest $request)
    {
        Campus::create($request->validated());

        alert()->success('Campus created successfully');
        return redirect()->route('campus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Campus $campus)
    {
        return response()->json($campus);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campus $campus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCampusRequest $request, Campus $campus)
    {
        $campus->update($request->validated());

        alert()->success('Campus updated successfully');
        return redirect()->route('campus.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        $campus->delete();
        
        alert()->success('Campus deleted successfully');
        return redirect()->route('campus.index');
    }
}
