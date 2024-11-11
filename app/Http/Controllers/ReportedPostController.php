<?php

namespace App\Http\Controllers;

use App\Models\DeletedPost;
use App\Models\Newsfeed;
use App\Models\ReportedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportedPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportedNewsfeeds = Newsfeed::where('status', 1)
            ->withWhereHas('reportedPosts', function ($query) {
                $query->whereIn('status', [1, 2]);
            })
            ->with([
                'user',
                'reportedPosts' => function ($query) {
                    $query->with(['user']);
                    $query->whereIn('status', [1, 2]);
                }
            ])
            ->withCount([
                'reportedPosts as report_count' => function ($query) {
                    $query->whereIn('status', [1, 2]);
                }
            ])
            ->get();

        $groupedByReportedPosts = [];

        foreach ($reportedNewsfeeds as $newsfeed) {
            foreach ($newsfeed->reportedPosts as $reportedPost) {
                $groupedByReportedPosts[$reportedPost->id][] = [
                    'newsfeed' => $newsfeed,
                    'reportedPost' => $reportedPost,
                    'user' => $reportedPost->user,
                ];
            }
        }

        return view('super-admin.reported-post.index', compact('groupedByReportedPosts'));
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
    public function update(Request $request, $id)
    {
        $reportedPost = ReportedPost::find($id);

        if ($request->status == 2) {
            // deleted

            DeletedPost::create([
                'newsfeed_id' => $request->newsfeed_id,
                'user_id' => $reportedPost->user_id,
                'reason' => $reportedPost->reason,
                'other_reason' => $reportedPost->other_reason,
                'status' => 0,
            ]);

            $newsfeed = Newsfeed::find($request->newsfeed_id);

            $newsfeed->update(['status' => 2]);

            if (isset($request->newsfeed_id)) {
                DB::table('reported_posts')
                    ->where('newsfeed_id', (int)$request->newsfeed_id)
                    ->update([
                        "status" => 2
                    ]);

                $sendMail = ReportedPost::where('newsfeed_id', (int)$request->newsfeed_id)
                    ->join('users', 'reported_posts.user_id', '=', 'users.id')
                    ->select('reported_posts.*', 'users.firstname', 'users.lastname', 'users.email')
                    ->get();

                foreach ($sendMail as $send) {
                    Mail::send([], [], function ($message) use ($request, $send) {
                        $htmlContent = "
                            <p>Dear " . $send->firstname . ' ' . $send->lastname . ",</p>
                            <p>Thank you for bringing the issue to our attention. After reviewing the report, we are pleased to inform you that the report has been approved.</p>
                            <p>If you have any further questions, feel free to contact us.</p>
                            <p>Best regards,<br> Super Admin</p>
                        ";

                        $message->to($send->email)
                            ->subject('Report Approved Notification')
                            ->html($htmlContent);
                    });
                }
            }
        }

        if ($request->status == 0) {
            if (isset($request->newsfeed_id)) {
                DB::table('reported_posts')
                    ->where('newsfeed_id', (int)$request->newsfeed_id)
                    ->update([
                        "status" => 0
                    ]);

                $sendMail = ReportedPost::where('newsfeed_id', (int)$request->newsfeed_id)
                    ->join('users', 'reported_posts.user_id', '=', 'users.id')
                    ->select('reported_posts.*', 'users.firstname', 'users.lastname', 'users.email')
                    ->get();

                foreach ($sendMail as $send) {
                    Mail::send([], [], function ($message) use ($request, $send) {
                        $htmlContent = "
                            <p>Dear " . $send->firstname . ' ' . $send->lastname . ",</p>
                            <p>Thank you for bringing the issue to our attention. After reviewing the report, we regret to inform you that the report has been declined.</p>
                            <p><strong>Reason for Decline:</strong> <br>" . $request->reason . "</p>
                            <p>If you have any further questions, feel free to contact us.</p>
                            <p>Best regards,<br> Super Admin</p>
                        ";

                        $message->to("kikomataks@gmail.com")
                            ->subject('Report Declined Notification')
                            ->html($htmlContent);
                    });
                }
            }
        }
        $alert_message = "'Reported post status has been updated'";

        if ($request->status == 0) {
            $alert_message = "Declined";
        }

        if ($request->status == 2) {
            $alert_message = "Approved";
        }

        alert()->success($alert_message);
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
