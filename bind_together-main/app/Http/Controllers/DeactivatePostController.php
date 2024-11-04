<?php

namespace App\Http\Controllers;

use App\Models\Newsfeed;
use Illuminate\Http\Request;

class DeactivatePostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $newsfeedId)
    {
        Newsfeed::find($newsfeedId)->update(['status' => 0]);

        alert()->success('Post deactivated successfully');
        return redirect()->route('newsfeed.index');
    }
}
