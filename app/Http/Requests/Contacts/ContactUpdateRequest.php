<?php

namespace App\Http\Requests\Contacts;

use App\Enums\BooleanType;
use App\Enums\ContactType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
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

            return auth()->user()->can('supplier_edit') && config('generalSettings')['subscription']->features['contacts'] == BooleanType::True->value;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = $this->route('type');
        $id = $this->route('id');
        $branchId = null;
        if ($type == ContactType::Customer->value) {

            $branchId = auth()->user()?->branch?->parent_branch_id ? auth()->user()?->branch?->parent_branch_id : auth()->user()->branch_id;
        } elseif ($type == ContactType::Supplier->value) {

            $branchId = auth()->user()->branch_id;
        }

        return [
            'name' => 'required|max:50',
            'phone' => ['required', 'max:50', Rule::unique('contacts', 'phone')->where(function ($query) use ($id, $branchId) {
                return $query->where('branch_id', $branchId)->where('id', '!=', $id);
            })],
        ];
    }
}
