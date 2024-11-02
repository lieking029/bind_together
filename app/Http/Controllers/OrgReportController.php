<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrgReportController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('admin-org.report-generation.index', ['organization' => Organization::all()]);
    }
}
