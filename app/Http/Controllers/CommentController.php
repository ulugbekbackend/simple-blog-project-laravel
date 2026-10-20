<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

/*
 * Izohlar uchun oddiy controller (to'liq resource shart emas — faqat 2 amal).
 * Django: ikki function-based view (`add_comment`, `delete_comment`).
 */
class CommentController extends Controller
{
    // POST /posts/{post}/comments
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        // make() — obyekt yasaydi, lekin hali saqlamaydi (Django: Comment(...) / form.save(commit=False)).
        // post_id bog'lanish orqali avtomatik qo'yiladi, user_id ni associate() bilan beramiz.
        $comment = $post->comments()->make($request->validated());
        $comment->user()->associate($request->user());
        $comment->save();

        return back()->with('success', "Izoh qo'shildi.");
    }

    // DELETE /comments/{comment}
    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', "Izoh o'chirildi.");
    }
}
