<?php

/*
 * Marshrutlar (routes) ≈ Django `urls.py` (urlpatterns).
 * ->name('...') ≈ path(..., name='...'); Blade'da route('posts.show', $post) ≈ {% url 'posts:show' post.slug %}.
 * middleware('auth') ≈ @login_required / LoginRequiredMixin.
 * Barcha marshrutlarni ko'rish: `php artisan route:list` (Django: show_urls, django-extensions).
 */

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/posts');

// Dashboard — joriy foydalanuvchining o'z postlari
Route::get('/dashboard', function () {
    return view('dashboard', [
        'posts' => auth()->user()->posts()->with('category')->withCount('comments')->latest()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Bitta qator 5 ta marshrut yaratadi: create, store, edit, update, destroy
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Ro'yxat va bitta postni ko'rish hamma uchun ochiq.
// Diqqat: bu `auth` guruhidan KEYIN turishi kerak, aks holda /posts/create
// so'rovi {post}="create" deb show() ga tushib qoladi.
Route::resource('posts', PostController::class)->only(['index', 'show']);

require __DIR__.'/auth.php';
