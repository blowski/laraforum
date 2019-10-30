<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    public function store(string $channelId, Thread $thread)
    {
        try {
            if(Gate::denies('create', new Reply)) {
                return response('You are posting too frequently', 422);
            }
            $this->authorize('create', new Reply);
            $this->validate(request(), [
                'body' => ['required', new SpamFree()],
            ]);
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch(ValidationException $exception) {
            return response(['message' => 'Your reply was not valid'], 422);
        }

        return $reply->load('owner');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        return response([], 204);
    }

    public function update(Reply $reply)
    {
        try {
            $this->validate(request(), [
                'body' => ['required', new SpamFree()],
            ]);
        } catch(ValidationException $exception) {
            return response(['message' => 'Your reply was not valid'], 422);
        }

        $this->authorize('update', $reply);

        $reply->update(['body' => request()->get('body')]);
    }

}
