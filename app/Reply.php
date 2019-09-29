<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string body
 */
class Reply extends Model
{
    use Favouritable;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
