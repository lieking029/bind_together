<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Mail\FeedbackResponse;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('super-admin.feedback.index', [
            'feedbacks' => Feedback::with('user')->where('status', 0)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedbackRequest $request)
    {
        Feedback::create($request->validated() + ['status' => 0, 'user_id' => Auth::id()]);

        alert()->success('Submitted Successfully');
        return redirect()->route('feedback.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        $feedback->update(['response' => $request->response, 'status' => 1]);
        $feedback->load('user');

        Mail::to($feedback->user->email)->send(new FeedbackResponse(
            $feedback->user->firstname, $request->response, Auth::user()->firstname,
        ));

        alert()->success('Response sent successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}
