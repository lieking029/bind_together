<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\DeletedComment;
use App\Models\ReportedComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function update(Request $request, int $reportedComment)
    {
        // $comment = $reportedComment->comments_id;

        $comment = Comments::with('reportedComments')->find($reportedComment);
        if ($request->status == 2) {
            $comment->update(['status' => 0]);
        }

        
        foreach ($comment->reportedComments as $rComment) {
            $rComment->update([
                'status' => $request->status, 
                'declined_reason' => $request->declined_reason ?? ''
            ]);
        }


        alert()->success('Reported comment status has been updated');
        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportedComment $reportedComment)
    {
        //
    }
}
