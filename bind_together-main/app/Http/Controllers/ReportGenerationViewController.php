<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Sport;
use Illuminate\Http\Request;

class ReportGenerationViewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('admin-sport.report-generation.index', ['sports' => Sport::all()]);
    }
}
