<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\VerifyUserEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(RegisterUserRequest $request)
    {

        $user = User::create([
            'student_number' => $request->student_number,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'suffix' => $request->suffix,
            'gender' => $request->gender,
            // 'contact' => $request->contact,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // if ($request->hasFile('profile')) {
        //     $profilePath = $request->file('profile')->store('avatar', 'public');
        //     $user->avatar = $profilePath;
        //     $user->save();
        // }

        $user->assignRole('student');

        $verificationUrl = $this->generateVerificationUrl($user);

        Mail::to($user->email)->send(new VerifyUserEmail(
            $user,
            $verificationUrl,
            $request->password,
            'student'
        ));

        alert()->success('Email verification has been sent')
            ->buttonsStyling(false)
            ->customCssClass(['confirmButton' => 'centered-button']);

        return redirect()->route('register');
    }


    protected function generateVerificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );
    }
}
