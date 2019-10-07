<reply :attributes='@json($reply)' inline-template v-cloak>
    <div class="card mt-2" id="reply-{{ $reply->id }}">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ $reply->owner->profilePath() }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
                </h5>
                <div>
                    <favourite :reply='@json($reply)'></favourite>
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
                <button class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
        </div>
        @endcan
    </div>
</reply>
