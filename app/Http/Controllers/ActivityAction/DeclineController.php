<?php

namespace App\Http\Controllers\ActivityAction;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DeclineController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Activity $activity)
    {
        $declineReason = $request->input('reason');

        $user = Auth::user();

        $activity->update(
            [
                'status' => 2,
            ]
        );

        Mail::send([], [], function ($message) use ($activity, $user, $declineReason) {
            $getRole = $user->getRoleNames();
            $coachName = $activity["user"]["firstname"] . ' ' . $activity["user"]["lastname"];
            $htmlContent = '
            <p>Dear Coach ' . $coachName . ',</p>
            <p>We regret to inform you that your recent tryouts post has been declined by the admin. The reason for this decision is as follows:</p>
            <p><strong>Reason for Declining:</strong><br>' . $declineReason . '</p>
            <p>You are welcome to make the necessary adjustments and resubmit the post for approval.</p>
            <p>If you have any questions or require further clarification, feel free to contact us.</p>
            <p>Best regards,<br>
            ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
            ' . ucfirst(($getRole[0] == 'admin_sport' ? 'Admin Sport' : $getRole[0])) . '</p>';

            $message->to($activity["user"]["email"])
                ->subject('Declined of Tryouts Post')
                ->html($htmlContent);
        });

        alert()->success('Declined!');

        return redirect()->route('activity.index');
    }
}
