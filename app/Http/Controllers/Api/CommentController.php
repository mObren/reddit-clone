<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * @param Comment $comment
     * @return JsonResponse
     */
    public function show(Comment $comment): JsonResponse
    {
        return response()->json(['data' => $comment]);
    }

    /**
     * @param Thread $thread
     * @return JsonResponse
     */
    public function showAllCommentsOfAThread(Thread $thread): JsonResponse
    {
        $comments = $thread->comments()->get();

        return response()->json(['data' => $comments]);
    }
    
    /**
     * store
     *
     * @param  Thread $thread
     * @param  StoreCommentRequest $request
     * @return JsonResponse
     */
    public function store(Thread $thread, StoreCommentRequest $request): JsonResponse
    {

        $validated = $request->validated();
        $userId = auth()->user()->id;
        $comment = new Comment;

        $comment->body = $validated['body'];
        $comment->user_id = $userId;
        $comment->commentable_id = $thread->id;
        $thread->comments()->save($comment);

        return response()->json(['message' => 'Your comment is added.','comment' => $comment->body]);

    }

        
    /**
     * replyStore
     *
     * @param  Thread $thread
     * @param  Comment $comment
     * @param  StoreCommentRequest $request
     * @return JsonResponse
     */
    public function replyStore(Thread $thread, Comment $comment, StoreCommentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userId = auth()->user()->id;
        $reply = new Comment;

        $reply->body = $validated['body'];
        $reply->user_id = $userId;
        $reply->parent_id = $comment->id;
        $thread->comments()->save($reply);

        return response()->json(['message' => 'Your reply has been added.','comment' => $reply->body]);
    }
}
