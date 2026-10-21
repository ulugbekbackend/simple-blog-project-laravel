<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Postni tahrirlash</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('posts.update', $post) }}" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                @csrf
                {{-- Resource route update() uchun PUT/PATCH kutadi --}}
                @method('PUT')
                @include('posts._form', ['post' => $post])
                <div class="flex items-center gap-4">
                    <x-primary-button>Yangilash</x-primary-button>
                    <a href="{{ route('posts.show', $post) }}" class="text-sm text-gray-600 hover:underline">Bekor qilish</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
