<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;

class RegisteredParticipantController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $status = $request->query('status', '0');

        $athletes = ActivityRegistration::query()
            ->with(['activity', 'user.campus'])
            ->where('status', $status)
            ->whereHas('activity', function ($query) {
                $query->whereIn('type', [ActivityType::Tryout, ActivityType::Practice]);
            })
            ->get();

        return view('coach.athlete-record.index', ['auditions' => $athletes, 'status' => $status]);
    }
}
