<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'space_id',
        'name',
        'link',
        'short',
    ];

    /**
     * Get the space that owns the Links
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    } 

    public function user() : HasOneThrough {
        return $this->hasOneThrough(User::class, Space::class, 'id', 'id', 'space_id', 'user_id' );
    }
}
