{{--
    Create va Edit uchun umumiy forma maydonlari.
    @include('posts._form') ≈ Django {% include "posts/_form.html" %}.
    old('title', $post->title) — validatsiya xatosidan keyin kiritilgan qiymatni qaytaradi,
    bo'lmasa modeldagi qiymatni (Django'da bound form buni o'zi qiladi).
    $errors — validatsiya xatolari (Django: form.title.errors).
--}}
@php($post ??= null)

<div>
    <x-input-label for="title" value="Sarlavha" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                  :value="old('title', $post?->title)" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div>
    <x-input-label for="category_id" value="Kategoriya" />
    <select id="category_id" name="category_id" required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">— tanlang —</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $post?->category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
</div>

<div>
    <x-input-label for="body" value="Matn" />
    <textarea id="body" name="body" rows="10" required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body', $post?->body) }}</textarea>
    <x-input-error :messages="$errors->get('body')" class="mt-2" />
</div>
