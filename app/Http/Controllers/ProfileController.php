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

        if ($request->hasFile('cert_registration')) {
            if ($user->cert_registration && Storage::exists('public/' . $user->cert_registration)) {
                Storage::delete('public/' . $user->cert_registration);
            }

            $certPath = $request->file('cert_registration')->store('activity_files', 'public');
            $user->update(['certificate_of_registration' => $certPath]);
        }

        
        if ($request->hasFile('photo_copy_id')) {
            if ($user->photo_copy_id && Storage::exists('public/' . $user->photo_copy_id)) {
                Storage::delete('public/' . $user->photo_copy_id);
            }

            $photoPath = $request->file('photo_copy_id')->store('activity_files', 'public');
            $user->update(['photo_copy_id' => $photoPath]);
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
            'is_completed' => 1
        ];

        if($request->person_to_contact != '' && $request->person_to_contact != null){
            $payload["person_to_contact"] =  $request->person_to_contact;
        }

        if($request->relationship != '' && $request->relationship != null){
            $payload["relationship"] =  $request->relationship;
        }

        if($request->emergency_contact != '' && $request->emergency_contact != null){
            $payload["emergency_contact"] =  $request->emergency_contact;
        }

        if(isset($request->sport_id)){
            $payload["sport_id"] = $request->sport_id;
        }

        $user->update($payload);

        alert()->success('Profile updated successfully!');
        return redirect()->route('profile.show');
    }
}
