<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Migratsiya — DB jadvalining tuzilishi.
 * Django: makemigrations o'zi models.py dan yaratadi, Laravel'da esa
 * migratsiyani QO'LDA yozasiz (model va migratsiya alohida fayllar).
 * Ishga tushirish: `php artisan migrate` ≈ `python manage.py migrate`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                       // Django: avtomatik `id` AutoField
            $table->string('name');             // CharField(max_length=255)
            $table->string('slug')->unique();   // SlugField(unique=True)
            $table->timestamps();               // created_at + updated_at (auto_now_add / auto_now)
        });
    }

    // Django'da orqaga qaytarish avtomatik; Laravel'da down() ni o'zingiz yozasiz.
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
