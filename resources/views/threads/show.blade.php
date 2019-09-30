@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                <a href="{{ $thread->creator->profilePath() }}">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}
                            </span>
                            @can('update', $thread)<form action="{{ $thread->path() }}" method="POST">{{ csrf_field() }}{{ method_field('DELETE') }}<button type="submit" class="btn btn-danger">Delete</button></form>@endcan
                        </div>
                    </div>
                    <div class="card-body">{{ $thread->body }}</div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                <div style="margin-top:15px">{{ $replies->links() }}</div>

                @if (auth()->check())
                    <form style="margin-top:15px" action="{{ $thread->path() . '/replies/' }}" method="POST">
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>This thread was published {{ $thread->created_at->diffForHumans() }} by <a href="#">{{ $thread->creator->name }}</a>, and currently has {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
