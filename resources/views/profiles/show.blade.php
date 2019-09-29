@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
            <hr class="my-4">
        </div>

        @foreach ($profileUser->threads as $thread)
            <div class="card" style="margin-top:15px">
                <div class="card-header">
                    <div class="level">
                        <h4 class="flex"><a href="{{ $thread->creator->profilePath() }}">{{ $thread->creator->name }}</a> posted: <a href="{{ $thread->path() }}">{{ $thread->title }}</a></h4>
                    </div>
                </div>
                <div class="card-body">{{ $thread->body }}</div>
            </div>
        @endforeach
    </div>
@endsection
