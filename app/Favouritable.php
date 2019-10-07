<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favouritable
{

    protected static function bootFavouritable()
    {
        static::deleting(function(Model $model) {
            $model->favourites()->get()->each->delete();
        });
    }

    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favourites()->where($attributes)->exists()) {
            $this->favourites()->create($attributes);
        }
    }

    public function unfavourite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favourites()->where($attributes)->get()->each->delete();
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

    public function getIsFavouritedAttribute()
    {
        return $this->isFavourited();
    }
}
