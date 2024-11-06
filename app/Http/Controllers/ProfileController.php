<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Campus;
use App\Models\Organization;
use App\Models\Program;
use App\Models\Sport;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {

        return view('auth.profile', [
            'organizations' => Organization::all(),
            'campuses' => Campus::all(),
            'programs' => Program::all(),
            'sports' => Sport::all(),
        ]);
    }

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->default('images/avatar/default.jpg'); // Default image path
        });
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
        }

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $payload = [
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'contact' => $request->contact,
            'campus_id' => $request->campus_id,
            'program_id' => $request->program_id,
            'gender' => $request->gender,
            'year_level' => $request->year_level,
            'organization_id' => $request->organization_id,
            'is_completed' => 1,
        ];

        if(isset($request->sport_id)){
            $payload["sport_id"] = $request->sport_id;
        }

        $user->update($payload);

        alert()->success('Profile updated successfully!');
        return redirect()->route('profile.show');
    }
}
