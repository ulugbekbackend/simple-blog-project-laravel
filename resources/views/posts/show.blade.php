<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>

            {{-- @can — Policy tekshiruvi shablonda. Tugmalar faqat muallifga ko'rinadi.
                 Django: {% if post.user == request.user %} (yoki django-rules: {% has_perm %}) --}}
            @can('update', $post)
                <div class="flex items-center gap-3">
                    <a href="{{ route('posts.edit', $post) }}" class="text-sm text-indigo-600 hover:underline">Tahrirlash</a>

                    {{-- HTML forma faqat GET/POST biladi; @method('DELETE') yashirin _method maydonini qo'shadi --}}
                    <form method="POST" action="{{ route('posts.destroy', $post) }}"
                          onsubmit="return confirm('Postni o\'chirishni tasdiqlaysizmi?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm text-red-600 hover:underline">O'chirish</button>
                    </form>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <article class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-sm text-gray-500 mb-4">
                    <a href="{{ route('posts.index', ['category' => $post->category->slug]) }}" class="hover:underline">{{ $post->category->name }}</a>
                    · {{ $post->user->name }} · {{ $post->created_at->format('d.m.Y H:i') }}
                </div>
                {{-- nl2br(e(...)) ≈ Django filtri |linebreaksbr (avval escape, keyin <br>) --}}
                <div class="text-gray-800 leading-relaxed">{!! nl2br(e($post->body)) !!}</div>
            </article>

            <section class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Izohlar ({{ $post->comments->count() }})</h3>

                @auth
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                        {{-- @csrf ≈ Django {% csrf_token %} --}}
                        @csrf
                        <textarea name="body" rows="3" placeholder="Izoh yozing..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        <x-primary-button class="mt-2">Yuborish</x-primary-button>
                    </form>
                @else
                    <p class="mb-6 text-sm text-gray-600">
                        Izoh yozish uchun <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">kiring</a>.
                    </p>
                @endauth

                <div class="space-y-4">
                    @forelse ($post->comments as $comment)
                        <div class="border-t pt-4">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>{{ $comment->user->name }} · {{ $comment->created_at->diffForHumans() }}</span>
                                @can('delete', $comment)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">o'chirish</button>
                                    </form>
                                @endcan
                            </div>
                            <p class="mt-1 text-gray-800">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Hali izoh yo'q.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
