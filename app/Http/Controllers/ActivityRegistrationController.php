<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRegistrationRequest;
use App\Models\Activity;
use App\Models\ActivityRegistration;
use App\Mail\ApproveTryout; // Ensure this is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRegistrationRequest $request)
    {
        $data = [
            'activity_id' => $request->activity_id,
            'height' => $request->height,
            'weight' => $request->weight,
            'contact_person' => $request->contact_person,
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $activityRegistrationId)
    {
        $request->validate([
            'status' => 'required|in:1,2',
        ]);

        $act = ActivityRegistration::with(['user', 'activity'])->findOrFail($activityRegistrationId);
        $act->update(['status' => $request->status]);

        $user = Auth::user();
        $data = [
            'user' => $act->user,
            'activity' => $act->activity,
            'admin' => $user,
        ];

        // Use your test email here
        $testEmail = 'willbarrios153@gmail.com'; // Replace with your actual email address

        // Check if the email sending was successful
        try {
            if ($request->status === 1) {
                Mail::to($testEmail)->send(new ApproveTryout(
                    'Registration Approved - Welcome to ' . $act->activity->title . '!',
                    'emails.approve',
                    $data
                ));
                alert()->success('Approved');
            } else if ($request->status === 2) {
                Mail::to($testEmail)->send(new ApproveTryout(
                    'Registration Status - ' . $act->activity->title,
                    'emails.decline',
                    $data
                ));
                alert()->success('Declined');
            }
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            alert()->error('Email sending failed: ' . $e->getMessage());
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
