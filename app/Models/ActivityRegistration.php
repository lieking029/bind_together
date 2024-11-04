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
        'sport_id', // Add sport_id if it's a foreign key in your table
        'height',
        'weight',
        'person_to_contact',
        'person_to_contact',
        'emergency_contact',
        'relationship',
        'certificate_of_registration',
        'photo_copy_id',
        'other_file',
        'parent_consent',
        'status',
        'is_deleted'
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class); // Adjust the foreign key if necessary
    }
}
