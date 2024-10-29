<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Sport;
use Illuminate\Http\Request;
use App\Models\Campus;
use App\Models\Program;

class ProfileCompletionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('auth.profile-completion', [
            'sports' => Sport::all(),
            'organizations' => Organization::all(),
            'campuses' => Campus::all(),
            'programs' => Program::all()
        ]);
    }
}
