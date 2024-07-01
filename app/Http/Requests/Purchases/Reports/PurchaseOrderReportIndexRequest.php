<?php

namespace App\Http\Requests\Purchases\Reports;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderReportIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('purchase_order_report') && config('generalSettings')['subscription']->features['purchase'] == BooleanType::True->value;
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
