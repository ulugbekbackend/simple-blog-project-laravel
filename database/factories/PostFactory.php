<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(5), '.');

        return [
            // Bog'liq modellar ham kerak bo'lsa factory orqali yaratiladi
            // (factory_boy: factory.SubFactory(UserFactory))
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            // Slug'ni shu yerda beramiz: seeder model hodisalarini o'chirib ishlaydi
            // (WithoutModelEvents), shuning uchun Post::booted() ishlamasligi mumkin.
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(5)),
            'body' => fake()->paragraphs(4, true),
            'created_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }
}
