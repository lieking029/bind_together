<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedCommentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $comment = Comments::find($request->comments_id);
        $user = Auth::user();

        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
        }

        $liked = $user->likedComments()->toggle($comment->id);

        $isLiked = $user->likedComments()->where('comments_id', $comment->id)->exists();

        return response()->json(['success' => true, 'liked' => $isLiked, 'message' => 'Like toggled successfully']);
    }
}
