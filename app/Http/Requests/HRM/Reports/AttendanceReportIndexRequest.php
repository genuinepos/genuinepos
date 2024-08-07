<?php

namespace App\Http\Requests\HRM\Reports;

use App\Enums\BooleanType;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceReportIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('attendance_report') && config('generalSettings')['subscription']->features['hrm'] == BooleanType::True->value;
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
