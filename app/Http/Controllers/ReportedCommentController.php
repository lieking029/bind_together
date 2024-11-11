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
                        <h1>APPROVE Comment</h1>";

                $message->to("kikomataks@gmail.com")
                    ->subject('Approve Comment')
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
                            <h1>DECLINE COMMENT</h1>";

                    $message->to("kikomataks@gmail.com")
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
