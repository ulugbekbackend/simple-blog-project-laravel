<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->with(['user', 'category'])
            ->withCount('comments')
            ->when($request->query('category'), fn ($query, $slug) => $query->whereRelation('category', 'slug', $slug))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $request->user()->posts()->create($request->validated());
        $post->load(['user', 'category'])->loadCount('comments');

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    public function show(Post $post): PostResource
    {
        $post->load(['user', 'category', 'comments' => fn ($q) => $q->with('user')->latest()])
            ->loadCount('comments');

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post->update($request->validated());
        $post->load(['user', 'category'])->loadCount('comments');

        return new PostResource($post);
    }

    public function destroy(Post $post): JsonResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}
