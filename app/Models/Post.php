<?php

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

// user_id ataylab fillable emas: uni formadan emas, controllerda $request->user() orqali beramiz.
#[Fillable(['category_id', 'title', 'body'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory;

    /*
     * Model hodisasi (event). Django'dagi `save()` ni override qilish yoki
     * `pre_save` signaliga o'xshaydi: post yaratilishidan oldin slug hosil qilinadi.
     */
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            $post->slug ??= static::uniqueSlug($post->title);
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'post'; // Django: django.utils.text.slugify
        $slug = $base;

        for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    /*
     * URL'da id emas, slug ishlatiladi: /posts/my-first-post
     * Django: path('posts/<slug:slug>/', ...) + get_object_or_404(Post, slug=slug)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Django: user = models.ForeignKey(User, ...)  ->  $post->user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Django: post.comment_set.all()  ->  $post->comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
