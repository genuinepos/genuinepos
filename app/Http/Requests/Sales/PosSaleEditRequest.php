<?php

namespace App\Http\Requests\Sales;

use App\Enums\SaleScreenType;
use Illuminate\Foundation\Http\FormRequest;

class PosSaleEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $saleScreenType = $this->route('saleScreenType');
        if ($saleScreenType == SaleScreenType::ServicePosSale->value) {

            return auth()->user()->can('service_invoices_edit');
        } else {

            return auth()->user()->can('pos_edit');
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
