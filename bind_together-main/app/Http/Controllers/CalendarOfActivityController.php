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
            ->get()
            ->filter(function ($activity) {
                $endDate = Carbon::parse($activity->end_date);
                // Exclude activities that have already ended
                return Carbon::now()->lte($endDate);
            })
            ->map(function ($activity) {
                $startDate = Carbon::parse($activity->start_date);
                $endDate = Carbon::parse($activity->end_date);

                // Check if the end date is earlier than the start date
                if ($endDate->lt($startDate)) {
                    // Set the end date to one hour after the start date if it's earlier
                    $endDate = $startDate->copy()->addHour();
                }

                return [
                    'title' => $activity->title,
                    'start' => $startDate->format('Y-m-d H:i:s'), // Format start date
                    'end' => $endDate->format('Y-m-d H:i:s'), // Format end date
                ];
            });

        // Return the view with the mapped activities
        return view('admin-sport.calendar-of-activity.index', ['activities' => $activities]);
    }

}
