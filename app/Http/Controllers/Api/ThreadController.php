<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ThreadController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $threads = Thread::all();
        return response()->json(["threads" => $threads]);
    }

    /**
     * @param StoreThreadRequest $request
     * @return JsonResponse
     */
    public function create(StoreThreadRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $thread = Thread::create($validated);
        return response()->json(['message' => 'Thread has been created.', 'thread' => $thread]);
    }

    /**
     * @param Thread $thread
     * @return JsonResponse
     */
    public function show(Thread $thread): JsonResponse
    {
        return response()->json(['thread' => $thread]);
    }

    /**
     * @param Thread $thread
     * @param StoreThreadRequest $request
     * @return JsonResponse
     */
    public function update(Thread $thread, StoreThreadRequest $request): JsonResponse
    {
        if (!$thread->isOlderThanSixHours()) {
            return response()->json(['message' => 'In order to edit a thread, you need to wait for six hours.'], 403);
        }
        $validated = $request->validated();

        if ($request->user()->id != $thread->user_id) {
            return response()->json(['message' => 'You are not authorized to edit this thread.'], 403);
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

        
    /**
     * postThreadToReddit
     *
     * @param  Thread $thread
     * @param  Request $request
     * @return JsonResponse
     */
    public function postThreadToReddit(Thread $thread, Request $request): JsonResponse
    {
        $url = 'https://oauth.reddit.com/api/live/create';
        if ($request->user()->id !== $thread->user_id) {
            return response()->json(['message' => 'You are unauthorized for this action.'], 403);
        }

        $response = Http::withToken($this->getRedditToken())->asForm()->post($url, [
            'title' => $thread->title,
            'description' => $thread->body
        ]);

        if ($response->status() === 200)
        {
            return response()->json(['message' => 'New thread on Reddit has been created successfuly.']);
        }
    }


    private function getRedditToken()
    {
        $url = "https://www.reddit.com/api/v1/access_token";
        $response = Http::acceptJson()->withBasicAuth(env('REDDIT_CLIENT_ID'), env('REDDIT_CLIENT_SECRET'))->asForm()->post($url, [
            'grant_type' => 'password',
            'username' => env('REDDIT_USERNAME'),
            'password' => env('REDDIT_PASSWORD')
        ]);
        return $response->object()->access_token;

    }
}
