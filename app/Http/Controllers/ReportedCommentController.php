<?php

namespace App\Http\Controllers;

use App\Mail\ApproveReport;
use App\Models\Comments;
use App\Models\DeletedComment;
use App\Models\ReportedComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportedCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportedComments = Comments::where('status', 1)
            ->withWhereHas('reportedComments', function ($query) {
                $query->whereIn('status', [1, 2]);
            })
            ->with([
                'user',
                'reportedComments' => function ($query) {
                    $query->with(['user']);
                    $query->whereIn('status', [1, 2]);
                }
            ])
            ->withCount([
                'reportedComments as report_count' => function ($query) {
                    $query->whereIn('status', [1, 2]);
                }
            ])
            ->get();

        return view('super-admin.reported-comment.index', compact('reportedComments'));
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
        // Validation
        $request->validate([
            'comments_id' => 'required|exists:comments,id',
            'reasons' => 'required|array|min:1',
            'reasons.*' => 'string',
            'other_reason' => 'nullable|string',
        ]);

        // Process the data
        $reasons = implode(', ', $request->reasons);
        $otherReason = $request->input('other_reason') ?? '';

        // Save the report to the database
        ReportedComment::create([
            'comments_id' => $request->comments_id,
            'user_id' => Auth::id(),
            'reason' => $reasons,
            'other_reason' => $otherReason,
            'status' => 1,
        ]);

        alert()->success('Success', 'Comment reported successfully!');
        return response()->json(['success' => true, 'message' => 'Comment reported successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportedComment $reportedComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReportedComment $reportedComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        $reportedComment = ReportedComment::with('comments.user', 'user')->where('comments_id', $id)->get();
        $comments = Comments::find($id);

        if ($request->status == 2) {

            $comments->update(['status' => 2]);

            DB::table('reported_comments')
                ->where('comments_id', $id)
                ->update([
                    "status" => 2
                ]);

            Mail::send([], [], function ($message) use ($comments) {
                $user = Auth::user();
                $htmlContent = "
                        <h1>APPROVE REPORT</h1>
                        <p>Dear ".$comments->user->firstname . " ". $comments->user->lastname .",</p>
                        <p>We hope this message finds you well. We want to inform you that your comment titled <b>".$comments->description."</b> was recently reported by another user for violating our community guidelines.</p>
                        <p>After careful review, we have determined that the comment does not adhere to our standards and policies. As a result, the comment will be removed from our platform.</p>
                        <p>We encourage you to review our community guidelines to ensure that future comments comply with our rules. If you believe this decision was made in error, please feel free to contact us for further clarification.</p>
                        <p>Thank you for your understanding and cooperation in maintaining a respectful community space.</p>
                        <p>Best regards,<br>{$user->firstname}<br>Admin</p>";

                $message->to($comments->user->email)
                    ->subject('Notice of Comment Removal Due to Community Guidelines Violation')
                    ->html($htmlContent);
            });
        }


        if ($request->status == 1) {
            DB::table('reported_comments')
                ->where('comments_id', $id)
                ->update([
                    "status" => 1
                ]);

            DB::table('comments')
                ->where('id', $id)
                ->update([
                    "status" => 1
                ]);
        }

        if ($request->status == 0) {
            DB::table('reported_comments')
                ->where('comments_id', $id)
                ->update([
                    "status" => 0
                ]);

            foreach ($reportedComment as $item) {
                Mail::send([], [], function ($message) use ($item, $request) {
                    $user = Auth::user();

                    $htmlContent = "
                        <h1>DECLINE REPORT</h1>
                        <p>Dear " . $item->user->firstname . " " . $item->user->lastname . ",</p>
                        <p>Thank you for bringing the reported comments to our attention. We have thoroughly reviewed your report and appreciate your concern.</p>
                        <p><strong>Reason for Declining:</strong> <br>" . $request->reason . "</p>
                        <p>Please understand that our decision is based on the established rules and standards we follow for content review. We encourage you to continue engaging with our platform and to reach out if you have any additional concerns.</p>
                        <p>Thank you for your vigilance in helping maintain the quality of our community.</p>
                        <p>Best regards,<br>" . $user->firstname . " " . $user->lastname . "<br>Admin</p>";

                    $message->to($item->user->email)
                        ->subject('Decline Post')
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
        return redirect()->route($is_restore ? 'deleted-comment.index' : 'reported-comment.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportedComment $reportedComment)
    {
        //
    }
}
