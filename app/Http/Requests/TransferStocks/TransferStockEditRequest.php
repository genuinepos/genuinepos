<?php

namespace App\Http\Requests\TransferStocks;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class TransferStockEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (
            !auth()->user()->can('transfer_stock_edit') ||
            config('generalSettings')['subscription']->features['transfer_stocks'] == BooleanType::False->value
        ) {
            return false;
        }

        if (
            config('generalSettings')['subscription']->has_business == BooleanType::False->value &&
            config('generalSettings')['subscription']->current_shop_count == 1 &&
            config('generalSettings')['subscription']->features['warehouse_count'] == 0
        ) {
            return false;
        }

        return true;
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
