<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/*
 * Factory — soxta (test) obyekt yasash uchun "retsept".
 * Django'da o'rnatilgan analogi yo'q; eng yaqini — `factory_boy` kutubxonasi
 * (DjangoModelFactory) va `Faker`. Bu yerda fake() = Faker.
 *
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => Str::ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
