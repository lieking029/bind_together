<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeletedCommentRequest;
use App\Models\Comments;
use App\Models\DeletedComment;
use App\Models\ReportedComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeletedCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reportedComments = Comments::withWhereHas('reportedComments', function ($query) {
            $query->where('status', 2); // Only get comments with status 0
        })
            ->with([
                'user', // Eager load the user for the comment
                'reportedComments' => function ($query) {
                    $query->where('status', 2); // Only load reportedComments with status 0
                }
            ])
            ->withCount([
                'reportedComments as report_count' => function ($query) {
                    $query->where('status', 0); // Count reportedComments with status 0
                }
            ])
            ->where('status', 2)
            ->get();

        return view('super-admin.deleted-comment.index', [
            'deletedComments' => $reportedComments,
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
    public function store(StoreDeletedCommentRequest $request)
    {
        $deleted = DeletedComment::create($request->validated() + ['user_id' => Auth::id()]);

        $deleted->comments()->update(['status' => 0]);

        alert()->success('Comment has been deleted');
        return redirect()->route('newsfeed.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DeletedComment $deletedComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeletedComment $deletedComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $commentId)
    {
        $deletedComment = Comments::find($commentId);
        $deletedComment->update(['status' => 1]);

        alert()->success('Comment restored successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletedComment $deletedComment)
    {
        //
    }
}
