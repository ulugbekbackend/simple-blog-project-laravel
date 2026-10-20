<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/*
 * Policy — bitta model uchun ruxsat qoidalari ("kim nima qila oladi").
 * Django'da to'g'ridan-to'g'ri analogi yo'q; odatda view ichida
 * `if post.user != request.user: raise PermissionDenied` yoki
 * UserPassesTestMixin / django-rules / django-guardian ishlatiladi.
 *
 * Laravel `Post` modeli uchun `PostPolicy` ni nomidan o'zi topadi (auto-discovery).
 * Ishlatish: $user->can('update', $post), Gate::authorize(...), Blade'da @can.
 */
class PostPolicy
{
    // `?User` — mehmon (login qilmagan) ham ko'ra oladi
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    // Asosiy qoida: faqat muallif tahrirlaydi
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
