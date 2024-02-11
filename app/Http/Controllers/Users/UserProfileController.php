<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Users\UserProfileService;

class UserProfileController extends Controller
{
    public function __construct(
        private UserProfileService $userProfileService,
        private UserService $userService,
    ) {
        $this->middleware('expireDate');
    }

    public function index()
    {
        return view('users.profile');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|unique:users,email,' . auth()->user()->id,
            'photo' => 'nullable|file|mimes:png,jpg,jpeg,gif,webp',
        ]);

        $updateUserProfile = $this->userProfileService->updateUserProfile(request: $request);

        session(['lang' => $updateUserProfile->language]);
        session()->flash('successMsg', __('User profile updated successfully'));
        return response()->json(__('Successfully user profile is updated'));
    }

    public function view($id)
    {
        $user = $this->userService->singleUser(id: $id, with: ['roles', 'department', 'shift', 'designation']);
        return view('users.view_profile', compact('user'));
    }
}
