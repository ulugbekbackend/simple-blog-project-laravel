<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/*
 * Eloquent model ≈ Django `models.Model`.
 * Farqi: maydonlar bu yerda e'lon qilinmaydi — Eloquent ularni jadvaldan
 * (migratsiyadan) o'zi oladi. Jadval nomi: `Category` -> `categories`.
 */
#[Fillable(['name', 'slug'])] // create()/update() da qaysi maydonlarni ommaviy berish mumkin (mass assignment himoyasi)
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    // Django: Post.category = ForeignKey(...) qilganda avtomatik `category.post_set` paydo bo'ladi.
    // Laravel'da teskari bog'lanishni o'zingiz yozasiz: $category->posts
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
