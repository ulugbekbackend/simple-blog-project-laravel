<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/*
 * Resource controller — CRUD uchun 7 ta standart metod.
 * Django'da eng yaqini: class-based views to'plami
 * (ListView, DetailView, CreateView, UpdateView, DeleteView)
 * yoki DRF'dagi ModelViewSet (list/retrieve/create/update/destroy).
 *
 * `Post $post` argumenti — Route Model Binding: Laravel URL'dagi slug bo'yicha
 * postni o'zi topadi, topilmasa 404. Django: get_object_or_404(Post, slug=slug).
 */
class PostController extends Controller
{
    // GET /posts  ≈ ListView
    public function index(Request $request): View
    {
        $posts = Post::query()
            // with() ≈ select_related(): N+1 so'rov muammosining oldini oladi
            ->with(['user', 'category'])
            // withCount() ≈ annotate(comments_count=Count('comments'))
            ->withCount('comments')
            // ?category=laravel bo'yicha filtr (Django: filter(category__slug=...))
            ->when($request->query('category'), fn ($query, $slug) => $query->whereRelation('category', 'slug', $slug))
            ->latest()                  // order_by('-created_at')
            ->paginate(10)              // Django: Paginator(qs, 10)
            ->withQueryString();        // sahifalashda ?category= saqlansin

        return view('posts.index', [
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    // GET /posts/create  ≈ CreateView (GET qismi — bo'sh forma)
    public function create(): View
    {
        return view('posts.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    // POST /posts  ≈ CreateView.form_valid()
    // Bu yerga kelguncha StorePostRequest validatsiyadan o'tkazib bo'lgan.
    public function store(StorePostRequest $request): RedirectResponse
    {
        // Django: form.instance.user = request.user; form.save()
        $post = $request->user()->posts()->create($request->validated());

        // with() — flash xabar (Django: messages.success(request, ...))
        return redirect()->route('posts.show', $post)->with('success', 'Post yaratildi.');
    }

    // GET /posts/{post}  ≈ DetailView
    public function show(Post $post): View
    {
        // Mavjud modelga bog'lanishlarni yuklash (prefetch_related ga o'xshash)
        $post->load(['user', 'category', 'comments' => fn ($q) => $q->with('user')->latest()]);

        return view('posts.show', compact('post'));
    }

    // GET /posts/{post}/edit  ≈ UpdateView (GET qismi)
    public function edit(Post $post): View
    {
        // Policy tekshiruvi; ruxsat bo'lmasa 403 (Django: raise PermissionDenied)
        Gate::authorize('update', $post);

        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    // PUT/PATCH /posts/{post}  — ruxsat UpdatePostRequest::authorize() da tekshiriladi
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()->route('posts.show', $post)->with('success', 'Post yangilandi.');
    }

    // DELETE /posts/{post}  ≈ DeleteView
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', "Post o'chirildi.");
    }
}
