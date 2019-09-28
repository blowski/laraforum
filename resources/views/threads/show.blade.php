@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header"><a href="#">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}
                    </div>
                    <div class="card-body">{{ $thread->body }}</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        <div class="row" style="margin-top: 30px">
            <div class="col-md-8 offset-md-2">
                @if (auth()->check())
                    <form action="{{ $thread->path() . '/replies/' }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" rows="5" class="form-control" placeholder="Have something to say?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in the discussion</p>
                @endif
            </div>
        </div>
    </div>
@endsection
