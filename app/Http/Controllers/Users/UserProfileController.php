<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use App\Services\Users\UserService;
use App\Http\Controllers\Controller;
use App\Services\Users\UserProfileService;
use App\Http\Requests\Users\UserProfileUpdateRequest;

class UserProfileController extends Controller
{
    public function __construct(
        private UserProfileService $userProfileService,
        private UserService $userService,
    ) {
        $this->middleware('subscriptionRestrictions');
    }

    public function index()
    {
        return view('users.profile');
    }

    public function update(UserProfileUpdateRequest $request)
    {
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
