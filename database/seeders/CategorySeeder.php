<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/*
 * Seeder — DB ni boshlang'ich ma'lumot bilan to'ldiradi.
 * Django: fixtures (`loaddata categories.json`) yoki data migration (RunPython).
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Laravel', 'Django', 'PHP', 'Python', 'JavaScript'] as $name) {
            // Django: Category.objects.get_or_create(slug=..., defaults={...})
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }
    }
}
