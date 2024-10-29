<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreCommentRequest $request)
    {
        $comment = Comments::create($request->validated() + ['user_id' => Auth::id()]);

        $comment_html = view('partials.comments', ['comment' => $comment->load('user')])->render();
        return response()->json(['success' => true, 'comment_html' => $comment_html,]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Comments $comments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comments $comments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $commentId)
    {
        $comments = Comments::find($commentId);
        $comments->update(['description' => $request->description]);

        return response()->json(['success' => true, 'comments' => $request->description]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $commentID)
    {
        Comments::find($commentID)->delete();

        return redirect()->back();
    }
}
