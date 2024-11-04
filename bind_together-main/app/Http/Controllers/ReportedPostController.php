<?php

namespace App\Http\Controllers;

use App\Models\DeletedPost;
use App\Models\ReportedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportedPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.reported-post.index', [
            'reportedNewsfeeds' => ReportedPost::with('newsfeed.user', 'user')
                ->where('status', '!=', 2)
                ->get()
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
    public function store(Request $request)
    {
        $request->validate([
            'newsfeed_id' => 'required|exists:newsfeeds,id',
            'reasons' => 'required|array|min:1',
            'reasons.*' => 'string',
            'other_reason' => 'nullable|string',
        ]);

        // Process the data
        $reasons = implode(', ', $request->reasons);
        $otherReason = $request->input('other_reason') ?? '';

        // Save the report to the database
        ReportedPost::create([
            'newsfeed_id' => $request->newsfeed_id,
            'user_id' => Auth::id(),
            'reason' => $reasons,
            'other_reason' => $otherReason,
            'status' => 1,
        ]);

        alert()->success('Success', 'Post reported successfully!');
        return response()->json([
            'message' => 'Post Reported Successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportedPost $reportedPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReportedPost $reportedPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReportedPost $reportedPost)
    {
        $reportedPost->update(['status' => $request->status]);

        if ($request->status == 2) {
            // deleted
            $reportedPost->newsfeed()->update(['status' => 2]);
        }

        DeletedPost::create([
            'newsfeed_id' => $reportedPost->newsfeed_id,
            'user_id' => $reportedPost->user_id,
            'reason' => $reportedPost->reason,
            'other_reason' => $reportedPost->other_reason,
            'status' => 0,
        ]);

        alert()->success('Reported post status has been updated');
        return redirect()->route('reported-post.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportedPost $reportedPost)
    {
        //
    }
}
