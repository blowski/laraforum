@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        Create a new thread
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/threads/">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="channel_id">Channel</label>
                                <select name="channel_id" id="channel_id" class="form-control custom-select" required>
                                    <option value="">-- Choose a channel --</option>
                                    @foreach ($channels as $channel)
                                        <option {{ $channel->id == old('channel_id') ? 'selected ' : '' }}value="{{ $channel->id }}">{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Title: </label>
                                <input type="text" class="form-control" id="title" name="title" required
                                       value="{{ old('title') }}">
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea class="form-control" id="body" name="body" required
                                          rows="8">{{ old('body') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </form>

                        @if (count($errors))
                            <ul class="alert alert-danger" style="margin-top:10px">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
