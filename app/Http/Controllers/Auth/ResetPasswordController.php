<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function resetCurrentPassword(Request $request)
    {
        $this->validate(
            $request,
            [
                'current_password' => 'required',
                'password' => 'required|confirmed',
            ]
        );

        $userHashedPassword = auth()->user()->password;
        $checkHashedPasswordWithOldPassword = Hash::check($request->current_password, $userHashedPassword);

        if ($checkHashedPasswordWithOldPassword) {

            if (!Hash::check($request->password, $userHashedPassword)) {

                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::logout();
                return response()->json(['successMsg' => __('Successfully password has been changed.')]);
            } else {

                return response()->json(['errorMsg' => __('Current password and new password is same.
                If you want to change your current password please enter a new password')]);
            }
        } else {

            return response()->json(['errorMsg' => __('Current password does not matched')]);
        }
    }
}
