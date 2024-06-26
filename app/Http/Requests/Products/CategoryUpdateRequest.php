<?php

namespace App\Http\Requests\Products;

use App\Enums\BooleanType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('product_category_edit') && config('generalSettings')['subscription']->features['inventory'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => ['required', Rule::unique('categories')->where(function ($query) use ($id) {
                return $query->where('parent_category_id', null)->where('id', '!=', $id);
            })],
            'photo' => 'sometimes|image|max:2048',
        ];
    }
}
