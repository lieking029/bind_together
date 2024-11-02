<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class AdviserReportController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('adviser.report-generation.index', ['organization' => Organization::all()]);
    }
}
