<?php

namespace Modules\SAAS\Http\Controllers;

use App\Utils\FileUploader;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\User;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules\Enum;
use Modules\SAAS\Enums\SupportedLanguage;
use Modules\SAAS\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit(User $user)
    {
        return view('saas::profile.edit', compact('user'));
    }
    public function update(ProfileUpdateRequest $request, User $user, FileUploader $fileUploader)
    {
        $profileRequest = $request->validated();
        if(isset($profileRequest['photo'])) {
            $profileRequest['photo'] = $fileUploader->uploadWithFullPath($profileRequest['photo'], 'uploads/users/avatar');
        }
        $user->update($profileRequest);
        return redirect()->back()->with('success', 'Profile updated!');
    }

    public function updateLanguage(Request $request)
    {
        $request->validate(['language' => [new Enum(SupportedLanguage::class)]]);
        $user = auth()->user();
        $user->language = $request->language;
        $user->save();
        if($user) {
            app()->setLocale($request->language);
        }
    }
}
