<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Models\Activity;
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
            $activities = Activity::whereIn('status', [0, 1])->get();
        } else {
            // Retrieve only activities for the authenticated user
            $activities = Activity::where('user_id', $user->id)
                ->whereIn('status', [0, 1])
                ->get();
        }

        return view('admin-sport.activity.index', [
            'activities' => $activities,
            'user' => $user,
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
        // Determine the status based on the type and user roles
        $status = 0; // Default status

        // Set status to 1 if the type is 2 or 3
        if (in_array($request->input('type'), [2, 3, 0])) {
            $status = 1;
        }

        // Set status to 1 if the user has 'admin_org' or 'admin_sport' roles
        if (auth()->user()->hasRole(['admin_org', 'admin_sport'])) {
            $status = 1;
        }

        // Handle file attachment if it exists
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

        // Create the activity
        Activity::create($data);

        // Show success message
        alert()->success('Activity created successfully');
        return redirect()->route('activity.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load('sport');
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
        $activity->update($request->validated() + ['status' => 0]);

        alert()->success('Activity updated successfully');
        return redirect()->route('activity.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
