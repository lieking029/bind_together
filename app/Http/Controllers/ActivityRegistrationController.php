<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRegistrationRequest;
use App\Models\Activity;
use App\Models\ActivityRegistration;
use App\Mail\ApproveTryout; // Ensure this is imported
use App\Models\Organization;
use App\Models\Sport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ActivityRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studentUserId = Auth::user()->id;
        $stud_user = Auth::user();

        $activities = Activity::where('status', 1)
            ->where('is_deleted', 0)
            ->where('end_date', '>=', now())
            ->get();

        $userIds = $activities->pluck('user_id')->unique();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $sportIds = $users->pluck('sport_id')->unique();
        $sports = Sport::whereIn('id', $sportIds)->get()->keyBy('id');

        $organizationIds = $users->pluck('organization_id')->unique();
        $organizations = Organization::whereIn('id', $organizationIds)->get()->keyBy('id');

        $activities->each(function ($activity) use ($users, $sports, $organizations, $studentUserId, $stud_user) {
            $activity->user = $users->get($activity->user_id) ?? null;

            if ($activity->user) {
                $activity->user->sport = $sports->get($activity->user->sport_id) ?? null;
                $activity->user->organization = $organizations->get($activity->user->organization_id) ?? null;
            }

            $is_visible = true;

            if ($activity->type == 0 && $activity->target_player == 1  && $activity->user->hasRole('adviser')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 0)
                    ->where('activities.target_player', 0)
                    ->where('activities.user_id', $activity->user_id)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();
                if (!$studentRegistrations) {
                    $is_visible = false;
                }
            }

            //check audition

            if ($activity->type == 0 && $activity->user->hasRole('adviser')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 0)
                    ->where('activities.id', '!=', $activity->id)
                    ->where('activities.user_id', $activity->user_id)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();

                if ($studentRegistrations) {
                    $is_visible = false;
                }
            }


            if ($activity->type == 2 && ($activity->target_player == 0 || $activity->target_player == 1) && $activity->user->hasRole('adviser')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 0)
                    ->where('activities.target_player', 0)
                    ->where('activities.user_id', $activity->user_id)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();
                if (!$studentRegistrations) {
                    $is_visible = false;
                }
            }

            if ($activity->type == 1 && $activity->target_player == 1  && $activity->user->hasRole('coach')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 1)
                    ->where('activities.target_player', 0)
                    ->where('activities.user_id', $activity->user_id)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();

                if (!$studentRegistrations) {
                    $is_visible = false;
                }
            }


            //check tryout

            if ($activity->type == 1 && $activity->user->hasRole('coach')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 1)
                    ->where('activities.id', '!=', $activity->id)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();

                if ($studentRegistrations) {
                    $is_visible = false;
                }
            }

            if ($activity->type == 2 && ($activity->target_player == 0 || $activity->target_player == 1) && $activity->user->hasRole('coach')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 1)
                    ->where('activities.target_player', 0)
                    ->where('activities.user_id', $activity->user_id)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();
                if (!$studentRegistrations) {
                    $is_visible = false;
                }
            }

            if ($activity->type == 3 && $activity->target_player == 1 && $activity->user->hasRole('admin_org')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 0)
                    ->where('activities.target_player', 0)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();
                if (!$studentRegistrations) {
                    $is_visible = false;
                }
            }

            if ($activity->type == 3 && $activity->target_player == 1 && $activity->user->hasRole('admin_sport')) {
                $studentRegistrations = DB::table('activity_registrations')
                    ->leftJoin('activities', 'activities.id', '=', 'activity_registrations.activity_id')
                    ->leftJoin('users', 'users.id', '=', 'activities.user_id')
                    ->leftJoin('sports', 'sports.id', '=', 'users.sport_id')
                    ->select('activities.type', 'activities.status', 'activities.target_player', 'activities.is_deleted', 'sports.id as sport_id')
                    ->where('activity_registrations.user_id', $studentUserId)
                    ->where('activities.status', 1)
                    ->where('activities.is_deleted', 0)
                    ->where('activities.type', 1)
                    ->where('activities.target_player', 0)
                    ->where('activity_registrations.is_deleted', 0)
                    ->where('activity_registrations.status', 1)
                    ->orderByDesc('activity_registrations.id')
                    ->limit(1)
                    ->first();
                if (!$studentRegistrations) {
                    $is_visible = false;
                }
            }

           if($is_visible){
                if ($activity->campuses != null) {
                    $rawCampuses = $activity->campuses; // Example: "[1 [1] => 2]"
                    $cleanedCampuses = str_replace(['[', ']'], '', $rawCampuses); // Remove extra brackets
                    $cleanedCampuses = preg_replace('/[^0-9,]/', '', $cleanedCampuses); // Remove non-numeric and non-comma characters
                    $campusesArray = explode(',', $cleanedCampuses); // Convert to array

                    foreach ($campusesArray as $value) {
                        if ((int)$stud_user->campus_id == (int)$value) {
                            $is_visible = true;
                            break;
                        } else {
                            $is_visible = false;
                        }
                    }
                }
           }

            $activity->is_visible = $is_visible;
        });

        return view('student.activity.index', compact('activities'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'activity_id' => $request->activity_id,
            'height' => $request->height,
            'weight' => $request->weight,
            'person_to_contact' => $request->person_to_contact,
            'emergency_contact' => $request->emergency_contact,
            'relationship' => $request->relationship,
            'user_id' => Auth::id(),
            'date_joining' => $request->date_joining,
        ];

        $fileFields = ['certificate_of_registration', 'parent_consent', 'other_file', 'photo_copy_id'];

        // Handle file uploads
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $filePath = $request->file($field)->store('activity_files', 'public');
                $data[$field] = $filePath;
            } else {
                $get_file = User::find(Auth::id());
                if ($get_file->$field) {
                    $data[$field] = $get_file->$field;
                }
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
        $act = ActivityRegistration::with(['user', 'activity'])->find($activityRegistrationId);
        if ($act->activity->type == 1 && (int)$request->status === 1) {
            $act->update(['status' => 1]);

            $user = Auth::user();

            Mail::send([], [], function ($message) use ($act, $user) {
                $getRole = $user->getRoleNames();
                $htmlContent = '
                <p>Dear ' . $act["user"]["firstname"] . ' ' . $act["user"]["lastname"] . ',</p>
                <p>We are pleased to inform you that your participation for ' . $act["activity"]["title"] . ' has been approved! We are excited to have you on board and look forward to seeing you participate.</p>
                <p>Please stay tuned for further updates and information.</p>
                <p>Best regards,<br>
                ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
                Coach</p>';

                $message->to($act["user"]["email"])
                    ->subject('Tryouts Approved - ' . $act["activity"]["title"])
                    ->html($htmlContent);
            });
            alert()->success('Approved');
        } else if ($act->activity->type == 1 && (int)$request->status === 2) {
            $act->update(['status' => 2]);

            $user = Auth::user();

            Mail::send([], [], function ($message) use ($act, $user, $request) {
                $getRole = $user->getRoleNames();
                $htmlContent = '
                    <p>Dear ' . $act["user"]["firstname"] . ' ' . $act["user"]["lastname"] . ',</p>
                    <p>Thank you for your interest in participating in ' . $act["activity"]["title"] . '. Unfortunately, we regret to inform you that you did not make it into the finals.</p>
                    <p><strong>Reason for Declining:</strong><br>
                        ' . $request->input('reason') . '</p>
                    <p>We appreciate your enthusiasm and encourage you to register for future activities. If you have any questions or would like to give feedback, please feel free to reach out.</p>
                    <p>Best regards,<br>
                    ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
                    Coach</p>';

                $message->to($act["user"]["email"])
                    ->subject('Tryouts Status - ' . $act["activity"]["title"])
                    ->html($htmlContent);
            });

            alert()->success('Declined');
        } else {
            if ((int)$request->status === 1) {
                $act->update(['status' => 1]);

                $user = Auth::user();

                Mail::send([], [], function ($message) use ($act, $user) {
                    $getRole = $user->getRoleNames();
                    $htmlContent = '
                    <p>Dear ' . $act["user"]["firstname"] . ' ' . $act["user"]["lastname"] . ',</p>
                    <p>We are pleased to inform you that your registration for ' . $act["activity"]["title"] . ' has been approved! We are excited to have you on board and look forward to seeing you participate.</p>
                    <p>Please stay tuned for further updates and information.</p>
                    <p>Best regards,<br>
                    ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
                    ' . ucfirst($getRole[0] == "admin_org" ? "Admin Org" : $getRole[0]) . '</p>';

                    $message->to($act["user"]["email"])
                        ->subject('Registration Approved - Welcome to ' . $act["activity"]["title"] . '!')
                        ->html($htmlContent);
                });
                alert()->success('Approved');
            } else if ((int)$request->status === 2) {
                $user = Auth::user();

                if ($user->hasRole('admin_sport')) {
                    $act->update(['status' => 2]);
                } else if ($user->hasRole('adviser')) {
                    $act->update(['status' => 2, 'is_deleted' => 1]);
                } else {
                    $act->update(['is_deleted' => 1]);
                }

                Mail::send([], [], function ($message) use ($act, $user, $request) {
                    $getRole = $user->getRoleNames();
                    $htmlContent = '
                        <p>SUBJECT: REGISTRATION STATUS UPDATE - ' . $act["activity"]["title"] . '</p>
                        <p>Dear ' . $act["user"]["firstname"] . ' ' . $act["user"]["lastname"] . ',</p>
                        <p>Thank you for registering for ' . $act["activity"]["title"] . '. After reviewing all applications, we regret to inform you that your registration is not approved.</p>
                        <p>Reason for declining:<br>
                        ' . $request->input('reason') . '</p>
                        <p>We encourage you to stay involved and consider applying for future activities. If you have any questions or need more information, please don’t hesitate to reach out.</p>
                        <p>Best regards,<br>
                        ' . $user["firstname"] . ' ' . $user["lastname"] . '<br>
                         ' . ucfirst($getRole[0] == "admin_org" ? "Admin Org" : $getRole[0]) . '</p>';

                    $message->to($act["user"]["email"])
                        ->subject('REGISTRATION STATUS UPDATE - ' . $act["activity"]["title"])
                        ->html($htmlContent);
                });

                alert()->success('Declined');
            } else {
                alert()->success('Updated successfully');
            }
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

        alert()->success('Archived');

        return redirect()->back();
    }

    public function permanentDelete($id)
    {
        $act = ActivityRegistration::find($id);

        if (!$act) {
            alert()->error('Record not found.');
            return redirect()->back();
        }

        $act->update(['is_deleted' => 2]);

        alert()->success('Deleted');

        return redirect()->back();
    }

    public function unarchive($id)
    {
        $act = ActivityRegistration::find($id);

        if (!$act) {
            alert()->error('Record not found.');
            return redirect()->back();
        }

        $act->update(['is_deleted' => 0]);

        alert()->success('Unarchived');

        return redirect()->back();
    }
}
