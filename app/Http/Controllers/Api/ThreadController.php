<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::all();
        return response()->json($threads);
    }

    public function create(StoreThreadRequest $request)
    {
        $validated = $request->validated();
        $thread = Thread::create($validated);
        return response()->json(['message' => 'Thread has been created.', 'data' => $thread]);
    }

    public function show(Thread $thread)
    {
        return response()->json(['data' => $thread]);
    }

    public function update(Thread $thread, StoreThreadRequest $request)
    {
        if (!$thread->isOlderThanSixHours()) {
            return response()->json(['message' => 'In order to edit a thread, you need to wait for six hours.']);
        }
        $validated = $request->validated();

        if ($validated['user_id'] != $thread->user_id) {
            return response()->json(['message' => 'You are not authorized to edit this thread.']);
        }
        $thread->update($validated);
        return response()->json(['message' => 'Thread has been updated.', 'data' => $validated]);
    }

    public function delete(Thread $thread)
    {
        Thread::destroy($thread->id);
        return response()->json(['message' => 'Thread has been deleted.']);
    }
}
