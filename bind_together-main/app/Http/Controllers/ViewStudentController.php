<?php

namespace App\Http\Controllers;

use App\Models\ActivityRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewStudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $userId)
    {
        $user = User::find($userId);

        $activityRegistrations = ActivityRegistration::whereHas('activity', function ($query) use ($userId) {
            $query->whereHas('user', function ($userQuery) use ($userId) {
                $userQuery->where('id', $userId);
            });
        })
            ->where('status', 1)
            ->with('user.campus', 'user.sport', 'user.organization')
            ->get();

        return view('admin-sport.view-student.index', ['students' => $activityRegistrations, 'superior' => $user]);
    }
}
