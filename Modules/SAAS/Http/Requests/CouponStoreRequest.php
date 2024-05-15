<?php

namespace Modules\SAAS\Http\Requests;

use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'code' => 'required|unique:coupons,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'percent' => 'required',
            'minimum_purchase_amount' => Rule::when($request->is_minimum_purchase == BooleanType::True->value, 'required|numeric'),
            'no_of_usage' => Rule::when($request->no_of_usage == BooleanType::True->value, 'required|numeric'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
