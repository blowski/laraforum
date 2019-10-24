<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Inspections\Spam;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(ThreadFilters $filters, Channel $channel)
    {
        $threads = $this->getThreads($filters, $channel);

        if(request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request, Spam $spam)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);
        if($spam->detect(request('body')) || $spam->detect(request('title'))) {
            throw new \Exception("Spam detected");
        }

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'title' => request('title'),
            'body' => request('body'),
            'channel_id' => request('channel_id'),
        ]);

        return redirect($thread->path())->with('flash', 'Your thread has been published');
    }

    public function show(string $channelId, Thread $thread)
    {
        /** @var User $user */
        if($user = auth()->user()) {
            $user->read($thread);
        }

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->delete();
        return redirect('/threads/');
    }

    private function getThreads(ThreadFilters $filters, Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->get();
    }
}
