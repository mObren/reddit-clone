<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreReplyRequest;
use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function show(Comment $comment)
    {
        return response()->json(['data' => $comment]);
    }

    public function showAllCommentsOfAThread(Thread $thread)
    {
        $comments = $thread->comments()->get();

        return response()->json(['data' => $comments]);
    }

    public function store(Thread $thread, StoreCommentRequest $request)
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

    public function replyStore(Thread $thread, Comment $comment, StoreCommentRequest $request)
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
