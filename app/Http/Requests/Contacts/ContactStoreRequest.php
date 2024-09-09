<?php

namespace App\Http\Requests\Contacts;

use App\Enums\ContactType;
use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $type = $this->route('type');
        if ($type == ContactType::Customer->value) {

            return auth()->user()->can('customer_add');
        } elseif ($type == ContactType::Supplier->value) {

            return auth()->user()->can('supplier_add');
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
            'name' => 'required',
            'phone' => 'required',
        ];
    }
}
