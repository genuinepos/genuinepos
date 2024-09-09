<?php

namespace App\Http\Requests\Sales;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CashRegisterClosedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        if (auth()->user()->can('register_close') && auth()->user()->can('another_register_close')) {

            return true;
        } else if (auth()->user()->can('register_close') && !auth()->user()->can('another_register_close')) {

            if (auth()->user()->id == $request->user_id) {

                return true;
            }
        } else if (!auth()->user()->can('register_close') && auth()->user()->can('another_register_close')) {

            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'closing_cash' => 'required'
        ];
    }
}
