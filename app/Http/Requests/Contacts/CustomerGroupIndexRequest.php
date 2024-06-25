<?php

namespace App\Http\Requests\Contacts;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('customer_group') && config('generalSettings')['subscription']->features['contacts'] == BooleanType::True->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
