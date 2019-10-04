<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public static function feed(User $user)
    {
        return $user
            ->activity()
            ->latest()
            ->with('subject')
            ->get()
            ->groupBy(function(Activity $activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
