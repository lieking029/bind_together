<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;

class AuditionListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $status = $request->query('status', '0');

        $auditions = ActivityRegistration::query()
            ->with(['activity', 'user'])
            ->where('status', $status)
            ->whereHas('activity', function ($query) {
                $query->where('type', ActivityType::Audition);
            })
            ->get();

        return view('adviser.performer-record.index', ['auditions' => $auditions, 'status' => $status]);
    }
}
