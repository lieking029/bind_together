<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedPost extends Model
{
    /** @use HasFactory<\Database\Factories\DeletedPostFactory> */
    use HasFactory;

    protected $fillable = [
        'newsfeed_id',
        'user_id',
        'reason',
        'other_reason',
        'status',
    ];

    public function newsfeed() : BelongsTo
    {
        return $this->belongsTo(Newsfeed::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
