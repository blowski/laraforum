<?php
declare(strict_types=1);

namespace App;

trait Favouritable
{

    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favourites()->where($attributes)->exists()) {
            $this->favourites()->create($attributes);
        }
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }

    public function getFavouritesCountAttribute(): int
    {
        return $this->favourites->count();
    }

    public function isFavourited(): bool
    {
        return $this->favourites->where('user_id', auth()->id())->count() > 0;
    }
}
