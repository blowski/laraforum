<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Collection replies
 */
class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function(Thread $thread) {
            $thread->replies->each->delete();
        });
    }

    public function path(): string
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply(array $reply)
    {
        return $this->replies()->create($reply);
    }

    public function subscribe(User $user)
    {
        $this
            ->subscriptions()
            ->create(['user_id' => $user->id])
        ;
    }

    public function unsubscribe(User $user)
    {
        $this
            ->subscriptions()
            ->where(['user_id' => $user->id])
            ->delete()
        ;
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

}
