@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-8 offset-md-2">

            <div class="jumbotron">
                <h1 class="display-4">
                    {{ $profileUser->name }}
                </h1>
                <p>Since {{ $profileUser->created_at->diffForHumans() }}</p>
            </div>

            @foreach ($activities as $date => $activity)
                <h3 class="mb-1">{{ $date }}</h3>
                @foreach ($activity as $record)
                    @include ("profiles.activities.{$record->type}", ['activity' => $record])
                @endforeach
            @endforeach

        </div>
    </div>
@endsection
