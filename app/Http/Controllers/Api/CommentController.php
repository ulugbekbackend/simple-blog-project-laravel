<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $post->comments()->make($request->validated());
        $comment->user()->associate($request->user());
        $comment->save();
        $comment->load('user');

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204);
    }
}
