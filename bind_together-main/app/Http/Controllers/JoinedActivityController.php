<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JoinedActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user()->load('joinedActivities.sport', 'practices.activity.sport');
        $joinedActivities = $user->joinedActivities;
        $practices = $user->practices;


        return view('student.activity.joined', ['joinedActivities' => $joinedActivities, 'practices' => $practices]);
    }
}
