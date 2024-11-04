<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comments extends Model
{
    /** @use HasFactory<\Database\Factories\CommentsFactory> */
    use HasFactory;

    protected $fillable = [
        'newsfeed_id',
        'user_id',
        'description',
        'status',
    ];

    public function newsfeed(): BelongsTo   
    {
        return $this->belongsTo(Newsfeed::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_likes', 'comments_id', 'user_id');
    }    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportedComments() : HasMany
    {
        return $this->hasMany(ReportedComment::class);
    }
}
