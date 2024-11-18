<?php

namespace App\Http\Controllers;

use App\Models\DeletedPost;
use App\Models\Newsfeed;
use App\Models\ReportedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
                },
                'newsfeedFiles'
            ])
            ->withCount([
                'reportedPosts as report_count' => function ($query) {
                    $query->whereIn('status', [1, 2]);
                }
            ])
            ->get();

        return view('super-admin.reported-post.index', compact('reportedNewsfeeds'));
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
        // $request->validate([
        //     'newsfeed_id' => 'required|exists:newsfeeds,id',
        //     'reasons' => 'required|array|min:1',
        //     'reasons.*' => 'string',
        //     'other_reason' => 'nullable|string',
        // ]);

        // Process the data
        $reasons = implode(', ', $request->reasons);
        $otherReason = $request->input('other_reason') ?? '';
        $filePath = null;

        if ($request->hasFile('txt-media')) {
            $path = $request->file('txt-media')->store('attachments', 'public');
            $filePath = $path;
        }

        // Save the report to the database
        ReportedPost::create([
            'newsfeed_id' => $request->newsfeed_id,
            'user_id' => Auth::id(),
            'reason' => $reasons,
            'other_reason' => $otherReason,
            'media' => $filePath,
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
        $reportedPost = ReportedPost::with('newsfeed.user', 'user')->where('newsfeed_id', $id)->get();
        $newsfeed = Newsfeed::with('user')->find($id);

        if ($request->status == 2) {

            $newsfeed->update(['status' => 2]);

            DB::table('reported_posts')
                ->where('newsfeed_id', $id)
                ->update([
                    "status" => 2
                ]);

            Mail::send([], [], function ($message) use ($newsfeed) {
                $user = Auth::user();
                $htmlContent = "
                        <h1>APPROVE POST</h1>
                        <p>Dear " . ($newsfeed->user->firstname . " " . $newsfeed->user->lastname) . ",</p>
                        <p>We hope this message finds you well. We want to inform you that your post, titled <b>" . $newsfeed->description . " </b> was recently reported by another user for violating our community guidelines.</p>
                        <p>After careful review, we have determined that the content does not adhere to our standards and policies. As a result, the post will be removed from our platform.</p>
                        <p>We encourage you to review our community guidelines to ensure that future posts comply with our rules. If you believe this decision was made in error, please feel free to contact us for further clarification.</p>
                        <p>Thank you for your understanding and cooperation in maintaining a respectful community space.</p>
                        <p>Best regards,<br>" . $user->firstname . " " . $user->lastname . "<br>Admin</p>
                    ";

                $message->to($newsfeed->user->email)
                    ->subject('Notice of Post Removal Due to Community Guidelines Violation')
                    ->html($htmlContent);
            });
        }


        if ($request->status == 1) {
            DB::table('reported_posts')
                ->where('newsfeed_id', $id)
                ->update([
                    "status" => 1
                ]);

            DB::table('newsfeeds')
                ->where('id', $id)
                ->update([
                    "status" => 1
                ]);
        }

        if ($request->status == 0) {
            DB::table('reported_posts')
                ->where('newsfeed_id', $id)
                ->update([
                    "status" => 0
                ]);

            foreach ($reportedPost as $item) {
                Mail::send([], [], function ($message) use ($item, $request) {
                    $user = Auth::user();
                    $htmlContent = "
                            <h1>DECLINE POST</h1>
                            <p>Dear " . ($item->user->firstname . " " . $item->user->lastname) . ",</p>
                            <p>Thank you for bringing the reported posts to our attention. We have thoroughly reviewed your report and appreciate your concern.</p>
                            <p><strong>Reason for Declining:</strong><br>
                            " . $request->reason . "</p>
                            <p>Please understand that our decision is based on the established rules and standards we follow for content review. We encourage you to continue engaging with our platform and to reach out if you have any additional concerns.</p>
                            <p>Thank you for your vigilance in helping maintain the quality of our community.</p>
                            <p>Best regards,<br>" . $user->firstname . " " . $user->lastname . "<br>Admin</p>
                        ";

                    $message->to($item->user->email)
                        ->subject('Response to Your Reported Post')
                        ->html($htmlContent);
                });
            }
        }

        $alert_message = "";
        $is_restore = false;

        if ($request->status == 0) {
            $alert_message = "Declined";
        }

        if ($request->status == 1) {
            $alert_message = "Restored";
            $is_restore  = true;
        }

        if ($request->status == 2) {
            $alert_message = "Approved";
        }

        alert()->success($alert_message);
        return redirect()->route($is_restore ? 'deleted-post.index' : 'reported-post.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportedPost $reportedPost)
    {
        //
    }
}
