<?php

namespace App\Http\Controllers;

use App\Models\ActivityRegistration;
use Illuminate\Http\Request;

class FetchActivityRegistration extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $activityId)
    {
        $activity = ActivityRegistration::with(['user.campus', 'activity'])->find($activityId);

        return response()->json($activity);
    }
}
