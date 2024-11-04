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
        return view('admin-sport.activity.deleted', [
            'activities' => Activity::where('user_id', Auth::id())
                ->where('status', 2)->get(),
            'user' => Auth::user()->load('organization')
        ]);
    }
}
