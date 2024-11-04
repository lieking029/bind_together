<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsfeedFile extends Model
{
    /** @use HasFactory<\Database\Factories\NewsfeedFileFactory> */
    use HasFactory;

    protected $fillable = [
        'newsfeed_id',
        'file_path',
        'file_type',
    ];

    public function newsfeed(): BelongsTo
    {
        return $this->belongsTo(Newsfeed::class);
    }
}
