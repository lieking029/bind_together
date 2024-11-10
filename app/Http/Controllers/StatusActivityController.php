<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class StatusActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $activityId)
    {
        $permanent = false;

        if((isset($request->delete_flag) && $request->delete_flag == 'permanent')){
            $permanent = true;
        }
        
        Activity::find($activityId)->update(['is_deleted' => $permanent ? 3 : 0]);

        alert()->success($permanent == false ? 'Unarchived'  :'Deleted');
        return redirect()->back();
    }
}
