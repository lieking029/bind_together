<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramRequest;
use App\Models\Campus;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.program.index', ['programs' => Program::with('campus')->get(), 'campuses' => Campus::all()]);
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
    public function store(StoreProgramRequest $request)
    {
        Program::create($request->validated());

        alert()->success('Program created successfully');
        return redirect()->route('program.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        $program->load('campus');

        return response()->json($program);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProgramRequest $request, Program $program)
    {
        $program->update($request->validated());

        alert()->success('Program updated successfully');
        return redirect()->route('program.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        alert()->success('Program deleted successfully');
        return redirect()->route('program.index');
    }
}
