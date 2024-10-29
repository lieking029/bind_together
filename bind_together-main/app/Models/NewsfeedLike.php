<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsfeedLike extends Model
{
    /** @use HasFactory<\Database\Factories\NewsfeedLikeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'newsfeed_id',
        'status',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function newsfeed() : BelongsTo
    {
        return $this->belongsTo(Newsfeed::class);
    }
}
