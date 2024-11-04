<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeletedPostRequest;
use App\Models\DeletedPost;
use App\Models\ReportedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeletedPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.deleted-post.index', [
            'deletedPosts' => DeletedPost::with('newsfeed.user', 'user')
                ->where('status', 0)
                ->get()
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
    public function store(StoreDeletedPostRequest $request)
    {
        $deleted = ReportedPost::create($request->validated() + [
            'user_id' => Auth::id()
        ]);

        $deleted->newsfeed()->update(['status' => 0]);

        alert()->success('Post has been deleted');
        return redirect()->route('newsfeed.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DeletedPost $deletedPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeletedPost $deletedPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeletedPost $deletedPost)
    {
        $deletedPost->update(['status' => 1]);
        $deletedPost->newsfeed()->update(['status' => 1]);

        alert()->success('Post restored successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletedPost $deletedPost)
    {
        //
    }
}
