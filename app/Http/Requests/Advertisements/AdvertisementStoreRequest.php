<?php

namespace App\Http\Requests\Advertisements;

use App\Enums\BooleanType;
use Illuminate\Validation\Rule;
use App\Enums\AdvertisementContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdvertisementStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('advertisements_create') && isset(config('generalSettings')['subscription']->features['advertisements']) && config('generalSettings')['subscription']->features['advertisements'] == BooleanType::True->value;
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
            'images' => Rule::when($request->content_type == AdvertisementContentType::Image->value, ['required', 'array', 'min:1']),
            'images.*' => Rule::when($request->content_type == AdvertisementContentType::Image->value, 'required|mimes:jpeg,jpg,png,gif,avif,webp|max:1024'),
            'video' => Rule::when($request->content_type == AdvertisementContentType::Video->value, 'required|mimes:mp4,avi,mov,wmv|max:102400'),
        ];
    }
}
