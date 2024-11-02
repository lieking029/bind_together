<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserTypeEnum;
use App\Mail\VerifyUserEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_number',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'birthdate',
        'gender',
        'address',
        'contact',
        'email',
        'year_level',
        'sport_id',
        'course_id',
        'campus_id',
        'program_id',
        'organization_id',
        'avatar',
        'is_active',
        'password',
        'is_completed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendCustomEmailVerification()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->id, 'hash' => sha1($this->getEmailForVerification())]
        );

        // Send the verification email using your Mailable
        Mail::to($this->email)->send(new VerifyUserEmail($this->name, $verificationUrl));
    }

    public function isSuperAdmin()
    {
        return $this->hasRole(UserTypeEnum::SUPERADMIN);
    }
    public function isAdminSport()
    {
        return $this->hasRole(UserTypeEnum::ADMINSPORT);
    }
    public function isAdminOrg()
    {
        return $this->hasRole(UserTypeEnum::ADMINORG);
    }
    public function isCoach()
    {
        return $this->hasRole(UserTypeEnum::COACH);
    }
    public function isAdviser()
    {
        return $this->hasRole(UserTypeEnum::ADVISER);
    }
    public function isStudent()
    {
        return $this->hasRole(UserTypeEnum::STUDENT);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }

    public function likedComments(): BelongsToMany
    {
        return $this->belongsToMany(Comments::class, 'comment_likes', 'user_id', 'comments_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function joinedActivities()
    {
        return $this->belongsToMany(Activity::class, 'activity_registrations', 'user_id', 'activity_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function practices(): HasMany
    {
        return $this->hasMany(Practice::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
