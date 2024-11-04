<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Practice extends Model
{
    /** @use HasFactory<\Database\Factories\PracticeFactory> */
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'status',
        'reason'
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
