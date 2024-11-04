<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityRegistration extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityRegistrationFactory> */
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'height',
        'weight',
        'emergency_contact',
        'relationship',
        'certificate_of_registration',
        'photo_copy_id',
        'other_file',
        'parent_consent',
        'status'
    ];

    public function activity() : BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
