<?php

namespace App\Http\Requests\Users\Reports;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class UserActivityLogReportIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('user_activities_log_index') && config('generalSettings')['subscription']->features['users'] == BooleanType::True->value;
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
