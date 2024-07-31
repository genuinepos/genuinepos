<?php

namespace App\Http\Requests\StockAdjustments\Reports;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentReportIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('stock_adjustment_report');
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
