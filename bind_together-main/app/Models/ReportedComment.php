<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportedComment extends Model
{
    /** @use HasFactory<\Database\Factories\ReportedCommentFactory> */
    use HasFactory;

    protected $fillable = [
        'comments_id',
        'user_id',
        'reason',
        'other_reason',
        'status',
        'declined_reason',
    ];

    public function comments(): BelongsTo
    {
        return $this->belongsTo(Comments::class);
    }  

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
