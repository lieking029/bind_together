<?php

namespace App\Http\Controllers\ActivityAction;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApproveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Activity $activity)
    {
        $user = Auth::user();

        $activity->update(['status' => 1]);

        Mail::send([], [], function ($message) use ($activity, $user) {
            $getRole = $user->getRoleNames();
            $coachName = $activity["user"]["firstname"] . ' ' . $activity["user"]["lastname"];
            $htmlContent = '
            <p>Dear Coach ' . $coachName . ',</p>
            <p>We are pleased to inform you that the activity post regarding your tryouts has been approved. You can now proceed with the tryouts as planned.</p>
            <p>If you have any further questions or need additional assistance, feel free to reach out to us.</p>
            <p>Thank you for your continued dedication as a coach!</p>
            <p>Best regards,<br>
            ' . $user["firstname"] . ' ' . $user["lastname"] . '</p>
            ' . ucfirst(($getRole[0] == 'admin_sport' ? 'Admin Sport' : $getRole[0])) . '</p>';

            $message->to($activity["user"]["email"])
                ->subject('Approval of Tryouts Post')
                ->html($htmlContent);
        });

        alert()->success('Approved!');
        return redirect()->route('activity.index');
    }
}
