<?php

namespace App\Http\Controllers;

use App\Models\ActivityRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    public function sendMessage(Request $request)
    {
        $campus_ids = $request->query('campus_ids');

        $campus_ids = json_decode($campus_ids);

        Log::info('sendMessage');

        // $request->validate([
        //     'description' => 'required|string|max:255',
        // ], [
        //     'description.max' => 'The description may not be greater than 255 characters.',
        // ]);

        $description = $request->input('description');
        Log::info($description);
        $users = User::whereIn('campus_id', $campus_ids)->pluck('contact');
        if ($users->isEmpty()) {
            return response()->json(['error' => 'No users found for the selected campus.'], 404);
        }
        // Add 0 in front of each number
        $mobileNumbers = $users->map(function ($number) {
            return '0' . $number;
        });

        // Convert the collection to a comma-separated string
        $mobileNumbersString = $mobileNumbers->implode(',');

        Log::info($mobileNumbersString);

        $apiKey = env('SMS_API_KEY');
        $message = $description; // Use the description text as the message

        $response = Http::post('https://semaphore.co/api/v4/messages', [
            'apikey' => $apiKey,
            'number' => $mobileNumbersString, // Use the comma-separated string
            'message' => $message,
        ]);

        Log::info($response->json());

        if ($response->successful()) {
            return back()->with('success', 'SMS sent successfully!');
        } else {
            return back()->with('error', 'Failed to send SMS.');
        }
    }

    public function sendMessageOfficialPlayers(Request $request)
    {
        $auth = Auth::user();

        $type = null;

        if($auth->hasRole('admin_org')){
            $type = 0;
        }

        if($auth->hasRole('admin_sport')){
            $type = 1;
        }

        $campus_ids = $request->query('campus_ids');

        $campus_ids = json_decode($campus_ids);

        Log::info('sendMessageOfficialPlayers');
        // $request->validate([
        //     'description' => 'required|string|max:255',
        // ], [
        //     'description.max' => 'The description may not be greater than 255 characters.',
        // ]);
        $description = $request->input('description');
        Log::info($description);
        // Get all official players
        $officialPlayers = ActivityRegistration::where('activity_registrations.status', 1)
            ->join('activities', 'activity_registrations.activity_id', '=', 'activities.id')
            ->where('activities.type', $type)
            ->distinct()
            ->pluck('activity_registrations.user_id');

        Log::info($officialPlayers);
        if ($officialPlayers->isEmpty()) {
            return response()->json(['error' => 'No official players found for the selected campus.'], 404);
        }
        // Get their contact information
        $contacts = User::whereIn('id', $officialPlayers)
            ->whereIn('campus_id', $campus_ids)
            ->pluck('contact');

        // Add 0 in front of each number
        $mobileNumbers = $contacts->map(function ($number) {
            return '0' . $number;
        });

        // Convert the collection to a comma-separated string
        $mobileNumbersString = $mobileNumbers->implode(',');

        Log::info($mobileNumbersString);

        $apiKey = env('SMS_API_KEY');
        $message = $description; // Use the description text as the message

        $response = Http::post('https://semaphore.co/api/v4/messages', [
            'apikey' => $apiKey,
            'number' => $mobileNumbersString, // Use the comma-separated string
            'message' => $message,
        ]);

        Log::info($response->json());

        if ($response->successful()) {
            return back()->with('success', 'SMS sent successfully!');
        } else {
            return back()->with('error', 'Failed to send SMS.');
        }
    }

    public function sendMultipleCampus (Request $request)
    {
        Log::info('sendMultipleCampus');
        $request->validate([
            'description' => 'required|string',
            'campus' => 'required|array',
        ]);
        $description = $request->input('description');
        $campuses = $request->input('campus');

    }

}
