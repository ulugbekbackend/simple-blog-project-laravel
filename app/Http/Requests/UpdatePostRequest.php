<?php

namespace App\Http\Requests;

/*
 * Qoidalar Store bilan bir xil, faqat authorize() farq qiladi.
 */
class UpdatePostRequest extends StorePostRequest
{
    // Policy'ga murojaat: PostPolicy::update($user, $post).
    // false qaytsa — 403 Forbidden. route('post') — URL'dagi {post} (slug bo'yicha topilgan model).
    // Django: UserPassesTestMixin.test_func() ga o'xshaydi.
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('post'));
    }
}
