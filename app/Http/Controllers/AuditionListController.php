<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditionListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user()->load('organization');

        // Get status from the request, defaulting to '0' if not provided
        $status = $request->query('status', '0');

        // Fetch audition registrations with related activity and user details
        $type   = null;

        if ($request->has('type')) {
            $type = $request->query('type');
        }

        $auditions = ActivityRegistration::query()
            ->with([
                'activity.user.roles',
                'activity.user.organization',
                'user.roles'
            ])
            ->whereIn('status', [$status, 1, 2])
            ->where('is_deleted', 0);

        if (!$user->hasRole('admin_org')) {
            $auditions->whereHas('activity', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }



        $auditions = $auditions->whereHas('activity', function ($query) use ($type) {
            if ($type == '3') {
                $query->where('type', ActivityType::Competition);
            } else {
                $query->where('type', ActivityType::Audition);
            }
        });

        $auditions = $auditions->get();

        // Return view with auditions and the status filter
        return view('adviser.performer-record.index', ['auditions' => $auditions, 'status' => $status]);
    }
}
