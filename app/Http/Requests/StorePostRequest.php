<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/*
 * Form Request ≈ Django `forms.ModelForm` ning validatsiya qismi.
 * Farqi: Django'da `form.is_valid()` ni o'zingiz chaqirasiz; Laravel'da
 * controller metodiga StorePostRequest yozilsa, validatsiya controllerdan
 * OLDIN avtomatik ishlaydi. Xato bo'lsa — formaga xatolar bilan qaytaradi
 * (`$errors` va `old()` Blade'da mavjud bo'ladi).
 */
class StorePostRequest extends FormRequest
{
    // Kim bu so'rovni yubora oladi? Post yaratish uchun login kifoya
    // (route'dagi `auth` middleware buni allaqachon ta'minlaydi).
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            // Django: ModelChoiceField(queryset=Category.objects.all())
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'body' => ['required', 'string', 'min:10'],
        ];
    }
}
