<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSportRequest;
use App\Models\Sport;
use Illuminate\Http\Request;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.sport.index', ['sports' => Sport::all()]);
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
    public function store(StoreSportRequest $request)
    {
        Sport::create($request->validated());

        alert()->success('Sport created successfully');
        return redirect()->route('sport.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sport $sport)
    {
        return response()->json($sport);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sport $sport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSportRequest $request, Sport $sport)
    {
        $sport->update($request->validated());

        alert()->success('Sport updated successfully');
        return redirect()->route('sport.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sport $sport)
    {
        $sport->delete();

        alert()->success('Sport deleted successfully');
        return redirect()->route('sport.index');
    }
}
