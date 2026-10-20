<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        // Har bir post uchun mavjud user/kategoriyadan tasodifiysini olamiz
        Post::factory(30)
            ->state(fn () => [
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
            ])
            ->create()
            ->each(function (Post $post) use ($users) {
                Comment::factory(rand(0, 5))->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                ]);
            });
    }
}
