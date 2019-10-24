<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Spam;
use App\Thread;
use Illuminate\Http\Request;

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

    public function store(Request $request, string $channelId, Thread $thread, Spam $spam)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        if($spam->detect(request('body'))) {
            throw new \Exception("Spam detected");
        }


        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

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
        $this->authorize('update', $reply);
        $reply->update(['body' => request()->get('body')]);
    }
}
