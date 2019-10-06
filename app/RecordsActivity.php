<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return;

        foreach(static::getActivitesToRecord() as $event) {
            static::$event(function(Model $model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function(Model $model) {
            $model->activity()->delete();
        });
    }

    protected static function getActivitesToRecord(): array
    {
        return ['created'];
    }

    protected function getActivityType(string $event): string
    {
        return sprintf('%s_%s', $event, strtolower((new \ReflectionClass($this))->getShortName()));
    }

    protected function recordActivity(string $event): void
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
