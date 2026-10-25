<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

// React frontend ishlatadigan JSON API testlari
class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_list_and_view_posts(): void
    {
        $post = Post::factory()->create();

        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonPath('data.0.slug', $post->slug)
            ->assertJsonPath('data.0.can_update', false);

        $this->getJson("/api/posts/{$post->slug}")
            ->assertOk()
            ->assertJsonPath('data.title', $post->title);
    }

    public function test_posts_can_be_filtered_by_category(): void
    {
        $category = Category::factory()->create();
        $inCategory = Post::factory()->create(['category_id' => $category->id]);
        Post::factory()->create();

        $this->getJson("/api/posts?category={$category->slug}")
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', $inCategory->slug);
    }

    public function test_user_can_register_login_and_logout(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Ali',
            'email' => 'ali@example.com',
            'password' => 'parol12345',
            'password_confirmation' => 'parol12345',
        ])->assertCreated()->assertJsonStructure(['token', 'user' => ['id', 'name']]);

        $token = $this->postJson('/api/login', [
            'email' => 'ali@example.com',
            'password' => 'parol12345',
        ])->assertOk()->json('token');

        $this->withToken($token)->getJson('/api/user')->assertOk()->assertJsonPath('data.name', 'Ali');
        $this->withToken($token)->postJson('/api/logout')->assertOk();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'noto-g-ri'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_guests_cannot_create_post(): void
    {
        $this->postJson('/api/posts', [])->assertUnauthorized();
    }

    public function test_user_can_create_update_and_delete_own_post(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Sanctum::actingAs($user);

        $slug = $this->postJson('/api/posts', [
            'title' => 'Birinchi post',
            'category_id' => $category->id,
            'body' => 'Bu yetarlicha uzun matn.',
        ])->assertCreated()->assertJsonPath('data.can_update', true)->json('data.slug');

        $this->putJson("/api/posts/{$slug}", [
            'title' => 'Yangilangan',
            'category_id' => $category->id,
            'body' => 'Yangilangan uzun matn.',
        ])->assertOk()->assertJsonPath('data.title', 'Yangilangan');

        $this->deleteJson("/api/posts/{$slug}")->assertNoContent();
        $this->assertDatabaseMissing('posts', ['slug' => $slug]);
    }

    public function test_post_validation_errors(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/posts', ['title' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'category_id', 'body']);
    }

    public function test_user_cannot_modify_others_post(): void
    {
        $post = Post::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/posts/{$post->slug}", [
            'title' => 'Hack',
            'category_id' => $post->category_id,
            'body' => 'Boshqaning posti matni.',
        ])->assertForbidden();
        $this->deleteJson("/api/posts/{$post->slug}")->assertForbidden();
    }

    public function test_comments_can_be_added_and_deleted(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $id = $this->postJson("/api/posts/{$post->slug}/comments", ['body' => 'Zo\'r post'])
            ->assertCreated()
            ->assertJsonPath('data.can_delete', true)
            ->json('data.id');

        $this->deleteJson("/api/comments/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('comments', ['id' => $id]);
    }

    public function test_user_cannot_delete_unrelated_comment(): void
    {
        $comment = Comment::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/comments/{$comment->id}")->assertForbidden();
    }
}
