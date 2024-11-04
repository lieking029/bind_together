<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarOfActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
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

        return view('admin-sport.calendar-of-activity.index', ['activities' => $activities]);
    }
}
