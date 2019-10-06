<reply :attributes='@json($reply)' inline-template v-cloak>
    <div class="card mt-2" id="reply-{{ $reply->id }}">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ $reply->owner->profilePath() }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
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
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group"><textarea v-model="body" class="form-control"></textarea></div>
                <button class="btn btn-sm btn-primary" @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>
        @can('update', $reply)
        <div class="card-footer">
            <div class="level">
                <button class="btn btn-primary mr-1 btn-sm" @click="editing = true">Edit</button>
                <button class="btn btn-danger" @click="destroy">Delete</button>
            </div>
        </div>
        @endcan
    </div>
</reply>
