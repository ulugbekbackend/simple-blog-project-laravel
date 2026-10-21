<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * Feature test ≈ Django `TestCase` + `self.client`.
 * RefreshDatabase — har bir test toza DB'da ishlaydi (Django test DB'si kabi).
 * actingAs($user) ≈ self.client.force_login(user).
 * Ishga tushirish: `php artisan test` ≈ `python manage.py test`.
 */
class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_posts(): void
    {
        $post = Post::factory()->create();

        $this->get('/posts')->assertOk()->assertSee($post->title);
        $this->get("/posts/{$post->slug}")->assertOk()->assertSee($post->title);
    }

    public function test_guests_are_redirected_from_create(): void
    {
        $this->get('/posts/create')->assertRedirect('/login');
    }

    public function test_user_can_create_post_and_slug_is_generated(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->post('/posts', [
            'title' => 'Mening birinchi postim',
            'category_id' => $category->id,
            'body' => 'Bu postning matni, yetarlicha uzun.',
        ])->assertRedirect('/posts/mening-birinchi-postim');

        $this->assertDatabaseHas('posts', [
            'slug' => 'mening-birinchi-postim',
            'user_id' => $user->id,
        ]);
    }

    public function test_duplicate_titles_get_unique_slugs(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $data = ['title' => 'Salom', 'category_id' => $category->id, 'body' => 'Yetarlicha uzun matn.'];

        $this->actingAs($user)->post('/posts', $data);
        $this->actingAs($user)->post('/posts', $data)->assertRedirect('/posts/salom-2');
    }

    public function test_validation_errors_are_returned(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/posts', ['title' => '', 'category_id' => 999, 'body' => 'qisqa'])
            ->assertSessionHasErrors(['title', 'category_id', 'body']);
    }

    public function test_only_author_can_edit_update_and_delete(): void
    {
        $post = Post::factory()->create();
        $stranger = User::factory()->create();
        $data = ['title' => 'Buzilgan', 'category_id' => $post->category_id, 'body' => 'Begona odam matni.'];

        $this->actingAs($stranger)->get("/posts/{$post->slug}/edit")->assertForbidden();
        $this->actingAs($stranger)->put("/posts/{$post->slug}", $data)->assertForbidden();
        $this->actingAs($stranger)->delete("/posts/{$post->slug}")->assertForbidden();

        $this->actingAs($post->user)->put("/posts/{$post->slug}", $data)->assertRedirect();
        $this->assertSame('Buzilgan', $post->fresh()->title);

        $this->actingAs($post->user)->delete("/posts/{$post->slug}")->assertRedirect('/posts');
        $this->assertModelMissing($post);
    }

    public function test_user_can_comment_and_delete_own_comment(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post("/posts/{$post->slug}/comments", ['body' => 'Zo\'r post!'])
            ->assertRedirect();

        $comment = Comment::firstOrFail();
        $this->assertSame($user->id, $comment->user_id);

        $this->actingAs(User::factory()->create())->delete("/comments/{$comment->id}")->assertForbidden();
        $this->actingAs($user)->delete("/comments/{$comment->id}")->assertRedirect();
        $this->assertModelMissing($comment);
    }
}
