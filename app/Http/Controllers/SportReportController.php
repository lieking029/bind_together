<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;

class SportReportController extends Controller
{
    /**
     * Display the report generation view for admin-sport.
     */
    public function __invoke(Request $request)
    {
        return view('admin-sport.report-generation.index', ['sports' => Sport::all()]);
    }
}
