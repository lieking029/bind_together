<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeletedActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user()->load('organization');

        if (auth()->user()->hasRole('admin_sport') || auth()->user()->hasRole('admin_org') || auth()->user()->hasRole('super_admin')) {
            if (auth()->user()->hasRole('admin_sport')) {
                $activities = Activity::where('is_deleted', 1)->where('user_id', $user->id)->get();
            } else {
                $activities = Activity::where('is_deleted', 1)
                    ->whereHas('user.roles', function ($query) {
                        $query->where('roles.id', '!=', 3);
                    })
                    ->get();
            }
        } else {
            $activities = Activity::where('user_id', Auth::id())
                ->where('is_deleted', 1)->get();
        }

        return view('admin-sport.activity.deleted', [
            'activities' => $activities,
            'user' => Auth::user()->load('organization'),
        ]);
    }
}
