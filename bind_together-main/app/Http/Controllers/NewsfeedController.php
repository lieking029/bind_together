<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsfeedRequest;
use App\Models\ActivityRegistration;
use App\Models\Campus;
use App\Models\Newsfeed;
use App\Models\NewsfeedFile;
use App\Models\ReportedComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsfeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsfeeds = Newsfeed::with([
            'user',
            'newsfeedFiles',
            'comments' => fn($q) => $q->where('status', 1)->with('user'),
            'newsfeedLikes'
        ])
            ->withCount([
                'newsfeedLikes as likes_count' => function ($query) {
                    $query->where('status', 1); // Count likes
                },
                'newsfeedLikes as dislikes_count' => function ($query) {
                    $query->where('status', 0); // Count dislikes
                },
            ])
            ->where('status', '!=', 2)
            ->get()
            ->filter(function ($newsfeed) {
                $authUser = Auth::user();

                if ($authUser->hasRole('student')) {
                    if (is_null($newsfeed->campus_id)) {
                        return true;
                    }

                    if ($newsfeed->campus_id == $authUser->campus_id) {

                        if (is_null($newsfeed->target_player)) {
                            return true;
                        }

                        if ($newsfeed->target_player == 'official_performers') {
                            $isOfficialPlayer = ActivityRegistration::where('user_id', $authUser->id)
                                ->where('status', 1)
                                ->exists();

                            return $isOfficialPlayer;
                        }

                        return true;
                    }

                    return false;
                }

                return true;
            })
            ->map(function ($newsfeed) {
                $newsfeed->user_liked = $newsfeed->newsfeedLikes
                    ->where('user_id', Auth::id())
                    ->where('status', 1)
                    ->isNotEmpty();

                $newsfeed->user_disliked = $newsfeed->newsfeedLikes
                    ->where('user_id', Auth::id())
                    ->where('status', 0)
                    ->isNotEmpty();

                return $newsfeed;
            });

        $campuses = Campus::all();

        return view('student.newsfeed.index', ['newsfeeds' => $newsfeeds, 'campuses' => $campuses]);
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
    public function store(StoreNewsfeedRequest $request)
    {
        $newsfeed = Newsfeed::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'campus_id' => $request->campus_id,
            'target_player' => $request->target_player,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filePath = $file->store('newsfeed_files', 'public'); // Storing in 'public/newsfeed_files'

                $fileType = $file->getMimeType();

                NewsfeedFile::create([
                    'newsfeed_id' => $newsfeed->id,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                ]);
            }
        }

        alert()->success('Posted successfully');
        return redirect()->route('newsfeed.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsfeed $newsfeed)
    {
        $newsfeed->load('newsfeedFiles');

        return response()->json($newsfeed);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsfeed $newsfeed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Newsfeed $newsfeed)
    {
        // Update the newsfeed description
        $newsfeed->update([
            'description' => $request->input('description'),
        ]);

        // Handle deletion of files if needed
        if ($request->has('deleted_files')) {
            $deletedFiles = array_filter(explode(',', $request->input('deleted_files'))); // Filter out empty values

            if (!empty($deletedFiles)) {
                NewsfeedFile::whereIn('id', $deletedFiles)->delete();
            }
        }

        // Handle new file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('newsfeed_files', 'public');

                NewsfeedFile::create([
                    'newsfeed_id' => $newsfeed->id,
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType()
                ]);
            }
        }

        // Load the updated newsfeed with its associated files
        $updatedNewsfeed = Newsfeed::with('newsfeedFiles')->find($newsfeed->id);

        // Return the updated newsfeed as JSON
        return response()->json([
            'newsfeed' => $updatedNewsfeed,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newsfeed $newsfeed)
    {
        $newsfeed->delete();

        alert()->success('Post deleted successfully');
        return redirect()->route('newsfeed.index');
    }
}
