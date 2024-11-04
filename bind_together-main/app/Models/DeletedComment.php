<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedComment extends Model
{
    /** @use HasFactory<\Database\Factories\DeletedCommentFactory> */
    use HasFactory;

    protected $fillable = [
        'comments_id',
        'user_id',
        'reason',
        'other_reason',
        'status',
    ];

    public function comments() : BelongsTo
    {
        return $this->belongsTo(Comments::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
