<div class="card" style="margin-top:10px">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="#">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
            </h5>
            <div>
                <form method="POST" action="/replies/{{ $reply->id }}/favourites/">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary" {{ $reply->isFavourited() ? 'disabled ' : '' }}>
                        {{ $reply->favourites_count }} {{ Str::plural('Favourite', $reply->favourites_count) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">{{ $reply->body }}</div>
</div>
