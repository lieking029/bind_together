<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarOfActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $auth = Auth::user();

        if ($auth->hasRole('student')) {
            $activities = Activity::where('activities.status', 1)
                ->where('activities.is_deleted', 0)
                ->where('activities.type', '!=', 2)
                ->join('activity_registrations', 'activity_registrations.activity_id', '=', 'activities.id')
                ->where('activity_registrations.user_id', $auth->id)
                ->select('activities.*', 'activity_registrations.user_id', 'activity_registrations.date_joining')
                ->get()
                ->filter(function ($activity) {
                    $endDate = Carbon::parse($activity->end_date);
                    return Carbon::now()->lte($endDate);
                })
                ->map(function ($activity) {
                    $startDate = Carbon::parse($activity->start_date);
                    $endDate = Carbon::parse($activity->end_date);

                    if ($endDate->lt($startDate)) {
                        $endDate = $startDate->copy()->addHour();
                    }

                    return [
                        'title' => $activity->title,
                        'start' => $activity->date_joining,
                        'start_time' => $startDate->format('Y-m-d H:i:s'),
                        'act_start' => $startDate->format('Y-m-d H:i:s'),
                        'act_end' => $endDate->format('Y-m-d H:i:s'),
                        'end_time' => $endDate->format('Y-m-d H:i:s'),
                        'venue' => $activity->venue,
                        'address' => $activity->address,
                        'date_joining' => $activity->date_joining
                    ];
                });
        } else {
            $activities = Activity::where('status', 1)
                ->where('is_deleted', 0)
                ->where('type', '!=', 2)
                ->get()
                ->filter(function ($activity) {
                    $endDate = Carbon::parse($activity->end_date);
                    return Carbon::now()->lte($endDate);
                })
                ->map(function ($activity) {
                    $startDate = Carbon::parse($activity->start_date);
                    $endDate = Carbon::parse($activity->end_date);

                    if ($endDate->lt($startDate)) {
                        $endDate = $startDate->copy()->addHour();
                    }

                    return [
                        'title' => $activity->title,
                        'start' => $startDate->format('Y-m-d H:i:s'),
                        'end' => $endDate->format('Y-m-d H:i:s'),
                    ];
                });
        }

        return view('admin-sport.calendar-of-activity.index', ['activities' => $activities]);
    }
}
