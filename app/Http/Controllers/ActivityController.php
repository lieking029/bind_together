<?php

namespace App\Http\Controllers;

use App\Enums\ActivityType;
use App\Http\Requests\StoreActivityRequest;
use App\Models\Activity;
use App\Models\ActivityRegistration;
use App\Models\Campus;
use App\Models\Organization;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->load('organization');

        if ($user->hasRole('super_admin') || $user->hasRole('admin_sport')) {
            $activities = Activity::whereIn('status', [0, 1])
                ->where('is_deleted', 0)
                ->whereIn('type', [
                    ActivityType::Tryout,
                    ActivityType::Competition,
                ])
                ->whereHas('user', function ($query) {
                    $query->whereDoesntHave('roles', function ($roleQuery) {
                        $roleQuery->where('id', 2);
                    });
                })
                ->get();
        } else {
            if ($user->hasRole('admin_org')) {
                $activities = Activity::with(['user.roles' => function ($query) {
                    $query->select('roles.id as role_id');
                }])
                    ->where('user_id', $user->id)
                    ->whereIn('status', [0, 1])
                    ->where('is_deleted', 0)
                    ->orWhere(function ($query) {
                        $query->whereHas('user.roles', function ($query) {
                            $query->where('roles.id', 4);
                        })
                            ->whereIn('status', [0, 1])
                            ->where('is_deleted', 0);
                    })
                    ->whereIn('type', [
                        ActivityType::Audition,
                        ActivityType::Competition
                    ])
                    ->get();
            } else {

                if ($user->hasRole('coach')) {
                    $activities = Activity::where('user_id', $user->id)
                        ->whereIn('status', [0, 1, 2])
                        ->where('is_deleted', 0)
                        ->get();
                } else {
                    $activities = Activity::where('user_id', $user->id)
                        ->whereIn('status', [0, 1])
                        ->where('is_deleted', 0)
                        ->get();
                }
            }
        }

        $campuses = Campus::all();

        return view('admin-sport.activity.index', [
            'activities' => $activities,
            'user' => $user,
            'campuses' => $campuses
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
    public function store(StoreActivityRequest $request)
    {
        $status = 0;

        if (in_array($request->input('type'), [2, 3, 0])) {
            $status = 1;
        }

        if (auth()->user()->hasRole(['admin_org', 'admin_sport'])) {
            $status = 1;
        }

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data = $request->except('attachment') + [
                'attachment' => $path,
                'user_id' => Auth::id(),
                'status' => $status
            ];
        } else {
            $data = $request->validated() + [
                'status' => $status,
                'user_id' => Auth::id()
            ];
        }

        if ($request->input('txtCampuses') && !empty($request->input('txtCampuses'))) {
            $string = $request->input('txtCampuses');
            $array = explode(',', $string);

            $array = array_map('intval', $array);
            $data["campuses"] = json_encode('[' . implode(',', $array) . ']');
        }

        Activity::create($data);

        alert()->success('Activity created successfully');
        return redirect()->route('activity.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load('sport');

        $user = User::find($activity->user_id);

        $org   = null;
        $sport = null;

        $activity["posted_by"] = $user->firstname . " " . $user->lastname;

        // if ($activity->type == 0) {
        $org = Organization::find($user->organization_id);
        // }

        // if ($activity->type == 1 || $activity->type == 2) {
        $sport = Sport::find($user->sport_id);
        // }

        $activity["organizations"] = $org;
        $activity["sports"] = $sport;

        $conflicts = ActivityRegistration::where('user_id', Auth::id())->whereIn('status', [0, 1])->where('is_deleted', 0)->whereNotNull('date_joining')->pluck('date_joining');

        $activity["conflicts"] = $conflicts;

        $activity["campuses"] = json_decode($activity->campuses, true);

        return response()->json($activity);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreActivityRequest $request, Activity $activity)
    {
        $payload = $request->validated();
        if ($activity->status == 2) {
            $payload["status"] = 0;
        }

        if ($request->input('txtCampuses') && !empty($request->input('txtCampuses'))) {
            $string = $request->input('txtCampuses');
            $array = explode(',', $string);

            $array = array_map('intval', $array);
            $payload["campuses"] = json_encode('[' . implode(',', $array) . ']');
        }

        $activity->update($payload);

        alert()->success('Activity updated successfully');
        return redirect()->route('activity.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->update([
            "is_deleted" => 1
        ]);

        alert()->success('Archived');
        return redirect()->route('activity.index');
    }
}
