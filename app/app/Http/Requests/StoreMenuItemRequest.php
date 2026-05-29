<?php

namespace App\Http\Requests;

use App\Enums\MenuItemType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isManager();
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:120'],
            'description'       => ['nullable', 'string', 'max:500'],
            'type'              => ['required', Rule::enum(MenuItemType::class)],
            'price'             => ['required', 'numeric', 'min:0.01', 'max:999.99'],
            'prep_time_minutes' => ['nullable', 'integer', 'min:1', 'max:180'],
            'calories'          => ['nullable', 'integer', 'min:0', 'max:9999'],
            'allergens'         => ['nullable', 'array'],
            'allergens.*'       => ['string'],
            'is_available'      => ['boolean'],
            'is_vegetarian'     => ['boolean'],
            'is_vegan'          => ['boolean'],
            'is_gluten_free'    => ['boolean'],
            'image'             => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
