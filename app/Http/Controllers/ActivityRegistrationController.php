<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRegistrationRequest;
use App\Models\Activity;
use App\Models\ActivityRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ActivityRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('student.activity.index', [
            'activities' => Activity::with('sport')->where('status', 1)->where('is_deleted', 0)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRegistrationRequest $request)
    {
        $data = [
            'activity_id' => $request->activity_id,
            'height' => $request->height,
            'weight' => $request->weight,
            'person_to_contact' => $request->person_to_contact,
            'emergency_contact' => $request->emergency_contact,
            'relationship' => $request->relationship,
            'user_id' => Auth::id(),
        ];

        $fileFields = ['certificate_of_registration', 'parent_consent', 'other_file', 'photo_copy_id'];

        // Handle file uploads
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $filePath = $request->file($field)->store('activity_files', 'public');
                $data[$field] = $filePath;
            }
        }

        ActivityRegistration::create($data);

        alert()->success('Activity registration created successfully.');

        return redirect()->route('activity-registration.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityRegistration $activityRegistration)
    {
        $activityRegistration->load('sport');

        return response()->json($activityRegistration);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityRegistration $activityRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $activityRegistrationId)
    {
        $act = ActivityRegistration::with(['user', 'activity'])->find($activityRegistrationId);
        $act->update(['status' => $request->status]);

        if ((int)$request->status === 1) {
            $user = Auth::user();
            Mail::send([], [], function ($message) use ($act, $user) {
                $htmlContent = '
                <p>Dear ' . $act["user"]["firstname"] . ' ' . $act["user"]["lastname"] . ',</p>
                <p>We are pleased to inform you that your registration for ' . $act["activity"]["title"] . ' has been approved! We are excited to have you on board and look forward to seeing you participate.</p>
                <p>Please stay tuned for further updates and information.</p>
                <p>Best regards,<br>
                ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
                Admin</p>';

                $message->to($act["user"]["email"])
                    ->subject('Registration Approved - Welcome to ' . $act["activity"]["title"] . '!')
                    ->html($htmlContent);
            });
            alert()->success('Approved');
        } else if ((int)$request->status === 2) {
            $user = Auth::user();
            Mail::send([], [], function ($message) use ($act, $user) {
                $htmlContent = '
                <p>Dear ' . $act["user"]["firstname"] . ' ' . $act["user"]["lastname"] . ',</p>
                <p>Thank you for registering for ' . $act["activity"]["title"] . '. After reviewing all applications, we regret to inform you that your registration has not been approved for this event.</p>
                <p>Please note that each activity/event has specific requirements, and some criteria were not fully met in this instance.</p>
                <p>We encourage you to stay involved and consider applying for future activities.</p>
                <p>If you have any questions or need more information, please don’t hesitate to reach out.</p>
                <p>Best regards,<br>
                ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
                Admin</p>';

                $message->to($act["user"]["email"])
                    ->subject('Registration Status - ' . $act["activity"]["title"])
                    ->html($htmlContent);
            });
            alert()->success('Declined');
        } else {
            alert()->success('Updated successfully');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityRegistration $activityRegistration)
    {
        //
    }

    public function deletion($id)
    {
        $act = ActivityRegistration::find($id);

        if (!$act) {
            alert()->error('Record not found.');
            return redirect()->back();
        }

        $act->update(['is_deleted' => 1]);

        alert()->success('Deleted successfully');

        return redirect()->back();
    }
}
