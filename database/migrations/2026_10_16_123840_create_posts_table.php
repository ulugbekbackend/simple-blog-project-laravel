<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Django: user = models.ForeignKey(User, on_delete=models.CASCADE)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Django: on_delete=models.PROTECT — postlari bor kategoriyani o'chirib bo'lmaydi
            $table->foreignId('category_id')->constrained()->restrictOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('body');               // Django: TextField()
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
