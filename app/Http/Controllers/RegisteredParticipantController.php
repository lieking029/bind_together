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
        $status = $request->query('status', '0') ?? '0';
        $deleted = $request->query('isArchived') ?? '0';
        $tryout = $request->query('isTryout') ?? null;

        $athletes = ActivityRegistration::query()
            ->with([
                'activity.user.sport',
                'user.campus',
                'sport'
            ])
            ->whereIn('status', [$status, 1, 2])
            ->whereHas('activity', function ($query) use ($tryout) {
                $query->where('type', $tryout !== null ? ActivityType::Tryout : ActivityType::Competition);
            })
            ->where('is_deleted', $deleted)
            ->get();

        return view('coach.athlete-record.index', ['auditions' => $athletes, 'status' => $status]);
    }
}
