<?php

namespace App\Http\Requests\Advertisements;

use Illuminate\Validation\Rule;
use App\Enums\AdvertisementContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdvertisementUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('advertisements_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'title' => 'required',
            'content_type' => 'required',
            'status' => 'required',
            // 'logo' => 'mimes:jpeg,jpg,png,gif,avif,webp|max:1024',
            'images' => Rule::when($request->content_type == AdvertisementContentType::Image->value, ['sometimes', 'array']),
            'images.*' => Rule::when($request->content_type == AdvertisementContentType::Image->value, 'sometimes|mimes:jpeg,jpg,png,gif,avif,webp|max:1024'),
            'video' => Rule::when($request->content_type == AdvertisementContentType::Video->value, 'sometimes|mimes:mp4,avi,mov,wmv|max:102400'),
        ];
    }
}
