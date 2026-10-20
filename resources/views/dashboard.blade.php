{{-- Login qilgan foydalanuvchining o'z postlari ($posts routes/web.php dan keladi) --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mening postlarim</h2>
            <a href="{{ route('posts.create') }}" class="text-sm text-indigo-600 hover:underline">+ Yangi post</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg divide-y">
                @forelse ($posts as $post)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <a href="{{ route('posts.show', $post) }}" class="font-medium text-gray-900 hover:underline">{{ $post->title }}</a>
                            <div class="text-xs text-gray-500">
                                {{ $post->category->name }} · {{ $post->comments_count }} ta izoh · {{ $post->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                        <a href="{{ route('posts.edit', $post) }}" class="text-sm text-indigo-600 hover:underline">Tahrirlash</a>
                    </div>
                @empty
                    <p class="p-6 text-gray-600">Sizda hali post yo'q.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
