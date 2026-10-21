{{--
    Postlar ro'yxati. Blade ≈ Django template.
    <x-app-layout> — komponent sifatidagi layout; ichidagi kontent {{ $slot }} ga tushadi.
    Django: {% extends "base.html" %} + {% block content %}.
    {{ $x }} avtomatik escape qiladi (Django'dagi {{ x }} kabi XSS'dan himoya).
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Blog</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Kategoriya filtri: ?category=slug --}}
            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('posts.index') }}"
                   class="px-3 py-1 rounded-full border {{ request('category') ? 'bg-white text-gray-600' : 'bg-gray-800 text-white' }}">
                    Hammasi
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('posts.index', ['category' => $category->slug]) }}"
                       class="px-3 py-1 rounded-full border {{ request('category') === $category->slug ? 'bg-gray-800 text-white' : 'bg-white text-gray-600' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- @forelse ≈ Django: {% for %} ... {% empty %} ... {% endfor %} --}}
            @forelse ($posts as $post)
                <article class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs text-gray-500 mb-1">
                        {{ $post->category->name }} · {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        <a href="{{ route('posts.show', $post) }}" class="hover:underline">{{ $post->title }}</a>
                    </h3>
                    {{-- Str::limit ≈ Django filtri |truncatechars:200 --}}
                    <p class="mt-2 text-gray-700">{{ Str::limit($post->body, 200) }}</p>
                    <div class="mt-3 text-sm text-gray-500">{{ $post->comments_count }} ta izoh</div>
                </article>
            @empty
                <p class="text-gray-600">Hozircha postlar yo'q.</p>
            @endforelse

            {{-- Sahifalash tugmalari (Tailwind uslubida tayyor) --}}
            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
