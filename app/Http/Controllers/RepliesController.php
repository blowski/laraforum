<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
use App\Thread;

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
            $this->validateReply();
            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time', 422);
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
            $this->validateReply();
            $this->authorize('update', $reply);
        } catch (\Exception $e) {
            return response('Sorry your reply could not be saved at this time', 422);
        }

        $reply->update(['body' => request()->get('body')]);
    }

    protected function validateReply(): void
    {
        $this->validate(request(), [
            'body' => 'required',
        ]);

        if (resolve(Spam::class)->detect(request('body'))) {
            throw new \Exception("Spam detected");
        }
    }
}
