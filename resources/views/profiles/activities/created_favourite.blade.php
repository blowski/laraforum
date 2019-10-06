@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->name }} favourited a <a href="{{ $activity->subject->favourited->path() }}">reply</a>
    @endslot
    @slot('body')
        {{ $activity->subject->favourited->body }}
    @endslot
@endcomponent
