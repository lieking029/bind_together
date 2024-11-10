<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredParticipantController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status') ?? '0';
        $deleted = $request->query('isArchived') ?? '0';
        $tryout = $request->query('isTryout') ?? null;
        $allTryout = $request->query('allTryout') ?? null;

        $stats = [$status, 2];

        if(!$allTryout){
            array_push($stats, 1);
        }

        $athletes = ActivityRegistration::query()
            ->with([
                'activity.user.sport',
                'user.campus',
                'sport'
            ])
            ->whereIn('status', $stats)
            ->whereHas('activity', function ($query) use ($tryout, $allTryout, $user) {
                if ($tryout) {
                    $query->where('type', ActivityType::Tryout);
                } else {
                    if ($allTryout) {
                        $query->where('type', ActivityType::Tryout);
                    } else {
                        if ($user->hasRole('coach')) {
                            $query->where('type', ActivityType::Tryout);
                        } else {
                            $query->where('type', ActivityType::Competition);
                        }
                    }
                }
            })
            ->where('is_deleted', $deleted)
            ->get();

        return view('coach.athlete-record.index', ['auditions' => $athletes, 'status' => $status]);
    }
}
