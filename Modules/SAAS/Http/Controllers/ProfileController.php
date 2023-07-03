<?php

namespace Modules\SAAS\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SAAS\Entities\User;
use Modules\SAAS\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit(User $user)
    {
        return view('saas::profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request, User $user)
    {
        $profileRequest = $request->validated();
        $user->update($profileRequest);
        return \redirect()->back()->with('success', 'Profile updated!');
    }
}
