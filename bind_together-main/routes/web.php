<?php

use App\Http\Controllers\ActivateUserController;
use App\Http\Controllers\ActivityAction\ApproveController;
use App\Http\Controllers\ActivityAction\DeclineController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityRegistrationController;
use App\Http\Controllers\AuditionListController;
use App\Http\Controllers\CalendarOfActivityController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DeactivatePostController;
use App\Http\Controllers\DeletedActivityController;
use App\Http\Controllers\DeletedCommentController;
use App\Http\Controllers\DeletedPostController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FetchActivityRegistration;
use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\JoinedActivityController;
use App\Http\Controllers\LikedCommentController;
use App\Http\Controllers\NewsfeedController;
use App\Http\Controllers\NewsfeedLikeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegisteredParticipantController;
use App\Http\Controllers\ReportCoachController;
use App\Http\Controllers\ReportedCommentController;
use App\Http\Controllers\ReportedPostController;
use App\Http\Controllers\ReportGenerationViewController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\StatusActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewStudentController;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendCustomEmailVerification();

    alert()->success('Email verification sent successfully');
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Auth::routes();


Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::view('about', 'about')->name('about');

    Route::post('users/activate/{id}', ActivateUserController::class);
    Route::post('liked-comments', LikedCommentController::class)->name('liked-comments');
    Route::get('calendar-of-activities', CalendarOfActivityController::class)->name('calendar-of-activities');
    Route::get('audition-list', AuditionListController::class)->name('audition.list');
    Route::get('registered-participant', RegisteredParticipantController::class)->name('registered.participant');
    Route::get('joined-activities', JoinedActivityController::class)->name('joined.activities');
    Route::get('activate-post/{newsfeedId}', DeactivatePostController::class)->name('deactivate-post');
    Route::get('report-view', ReportGenerationViewController::class)->name('report.view');
    Route::post('report-generate', GenerateReportController::class)->name('reports.generate');
    Route::put('comments/{commentId}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('newsfeed-like', NewsfeedLikeController::class)->name('newsfeed.like');
    Route::get('fetch-activity/{activityId}', FetchActivityRegistration::class)->name('fetch.activities');
    Route::post('delete-activity/{activityId}', StatusActivityController::class)->name('delete.activity');
    Route::get('deleted-activities', DeletedActivityController::class)->name('deleted.activities');
    Route::get('view-user/{userId}', ViewStudentController::class)->name('view.user');
    Route::get('report-coach', [ReportCoachController::class, 'index'])->name('report.coach');
    Route::post('report-coach', [ReportCoachController::class, 'generateReport'])->name('report.generate');
    Route::get('profile-completion', ProfileCompletionController::class)->name('profile.completion');

    Route::put('approve-activity/{activity}', ApproveController::class)->name('approve');
    Route::put('decline-activity/{activity}', DeclineController::class)->name('decline');

    Route::resource('users', UserController::class);
    Route::resource('campus', CampusController::class);
    Route::resource('program', ProgramController::class);
    Route::resource('sport', SportController::class);
    Route::resource('organization', OrganizationController::class);
    Route::resource('newsfeed', NewsfeedController::class);
    Route::resource('comments', CommentController::class)->except('update', 'destroy');
    Route::resource('reported-comment', ReportedCommentController::class);
    Route::resource('reported-post', ReportedPostController::class);
    Route::resource('activity', ActivityController::class);
    Route::resource('activity-registration', ActivityRegistrationController::class);
    Route::resource('deleted-post', DeletedPostController::class);
    Route::resource('deleted-comment', DeletedCommentController::class);
    Route::resource('feedback', FeedbackController::class);
    Route::resource('practice', PracticeController::class);

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
