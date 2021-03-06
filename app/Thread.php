<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Providers\UserRepliedToThread;
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

    protected $appends = ['isSubscribedTo'];

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
        /** @var Reply $reply */
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function subscribe(User $user)
    {
        $this
            ->subscriptions()
            ->create(['user_id' => $user->id])
        ;
        return $this;
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

    public function getIsSubscribedToAttribute(): bool
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function hasUpdatesFor(?User $user): bool
    {
        if(null === $user) {
            return false;
        }

        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }

}
