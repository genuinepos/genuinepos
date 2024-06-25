<?php

namespace App\Http\Requests\Contacts;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use Illuminate\Foundation\Http\FormRequest;

class ContactEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $type = $this->route('type');
        if ($type == ContactType::Customer->value) {

            return auth()->user()->can('customer_edit') && config('generalSettings')['subscription']->features['contacts'] == BooleanType::True->value;
        } elseif ($type == ContactType::Supplier->value) {

            return auth()->user()->can('customer_edit') && config('generalSettings')['subscription']->features['contacts'] == BooleanType::True->value;
        }
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
