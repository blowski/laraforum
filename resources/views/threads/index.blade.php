@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                @foreach($threads as $thread)
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="level">
                                <h4 class="flex mb-0"><a href="{{ $thread->path() }}">{{ $thread->title }}</a></h4>
                                <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
