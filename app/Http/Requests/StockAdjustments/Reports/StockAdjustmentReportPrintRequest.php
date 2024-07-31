<?php

namespace App\Http\Requests\StockAdjustments\Reports;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentReportPrintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('stock_adjustment_report') && config('generalSettings')['subscription']->features['stock_adjustments'] == BooleanType::True->value;
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
