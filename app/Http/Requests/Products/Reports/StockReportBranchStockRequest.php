<?php

namespace App\Http\Requests\Products\Reports;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class StockReportBranchStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('stock_report') && config('generalSettings')['subscription']->features['inventory'] == BooleanType::True->value;
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
