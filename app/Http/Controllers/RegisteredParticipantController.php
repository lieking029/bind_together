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

        $stats = [$status];

        if (!$allTryout) {
            array_push($stats, 1);
        } else {
            array_push($stats, 2);
        }

        if ($user->hasRole('coach') && $deleted != 0) {
            array_push($stats, 2);
        }

        $athletes = ActivityRegistration::query()
            ->with([
                'activity.user.sport',
                'user.campus',
                'sport'
            ])
            ->whereIn('status', $stats)
            ->whereHas('activity', function ($query) use ($tryout, $allTryout, $user) {
                $query->join('users', 'users.id', '=', 'activities.user_id');

                if ($tryout) {
                    $query->where('activities.type', ActivityType::Tryout);
                } else {
                    if ($allTryout) {
                        $query->where('activities.type', ActivityType::Tryout);
                        if ($user->hasRole('coach')) {
                            $query->where('users.sport_id', $user->sport->id); 
                        }
                    } else {
                        if ($user->hasRole('coach')) {
                            $query->where('activities.type', ActivityType::Tryout)
                                ->where('users.sport_id', $user->sport->id); 
                        } else {
                            if($user->hasRole('admin_sport')){
                                $query->where('activities.user_id', $user->id);
                            }
                            $query->where('activities.type', ActivityType::Competition);
                        }
                    }
                }
            })
            ->where('is_deleted', $deleted)
            ->get();


        return view('coach.athlete-record.index', ['auditions' => $athletes, 'status' => $status]);
    }
}
