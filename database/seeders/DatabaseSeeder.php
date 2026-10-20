<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/*
 * Asosiy seeder: `php artisan db:seed` (yoki `migrate:fresh --seed`) shuni ishga tushiradi.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Kirish uchun tayyor akkaunt (paroli factory'da: "password")
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(4)->create();

        // Tartib muhim: post uchun avval user va kategoriya bo'lishi kerak
        $this->call([
            CategorySeeder::class,
            PostSeeder::class,
        ]);
    }
}
