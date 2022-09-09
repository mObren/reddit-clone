<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $threads = Thread::all();
        return response()->json($threads);
    }

    /**
     * @param StoreThreadRequest $request
     * @return JsonResponse
     */
    public function create(StoreThreadRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $thread = Thread::create($validated);
        return response()->json(['message' => 'Thread has been created.', 'data' => $thread]);
    }

    /**
     * @param Thread $thread
     * @return JsonResponse
     */
    public function show(Thread $thread): JsonResponse
    {
        return response()->json(['data' => $thread]);
    }

    /**
     * @param Thread $thread
     * @param StoreThreadRequest $request
     * @return JsonResponse
     */
    public function update(Thread $thread, StoreThreadRequest $request): JsonResponse
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

    /**
     * @param Thread
     * @return JsonResponse
     */
    public function delete(Thread $thread): JsonResponse
    {
        Thread::destroy($thread->id);
        return response()->json(['message' => 'Thread has been deleted.']);
    }
}
