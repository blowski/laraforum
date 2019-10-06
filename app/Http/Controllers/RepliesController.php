<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, string $channelId, Thread $thread)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        return back();
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

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(['body' => request()->get('body')]);
    }
}
