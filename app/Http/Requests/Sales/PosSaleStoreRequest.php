<?php

namespace App\Http\Requests\Sales;

use App\Enums\BooleanType;
use App\Enums\SaleScreenType;
use Illuminate\Foundation\Http\FormRequest;

class PosSaleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $saleScreenType = $this->route('saleScreenType');
        if ($saleScreenType == SaleScreenType::ServicePosSale->value) {

            return auth()->user()->can('service_invoices_create') && (isset(config('generalSettings')['subscription']->features['services']) && config('generalSettings')['subscription']->features['services'] == BooleanType::True->value);
        } else {

            return auth()->user()->can('pos_add') && config('generalSettings')['subscription']->features['sales'] == BooleanType::True->value;
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
