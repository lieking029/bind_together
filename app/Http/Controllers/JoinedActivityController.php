<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityRegistration;
use App\Models\Organization;
use App\Models\Sport;
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


        foreach ($joinedActivities as $activity) {
            $user = User::find($activity->user_id);

            $activity["posted_by"] = $user->firstname . " " . $user->lastname;

            if ($activity->type == 0 ) {
                $activity["organizations"] = Organization::find($user->organization_id);
            }

            if ($activity->type == 1 || $activity->type == 2) {
                $activity["sports"] = Sport::find($user->sport_id);
            }
        }

        foreach($practices as $practice){
            $user = User::find($practice->user_id);
            $practice["posted_by"] = $user->firstname . " " . $user->lastname;

            $activity = Activity::find($practice->activity_id);
            $_user =  User::find($activity->user_id);;
            $practice["sports"] = Sport::find($_user->sport_id);
        }

        return view('student.activity.joined', ['joinedActivities' => $joinedActivities, 'practices' => $practices]);
    }
}
