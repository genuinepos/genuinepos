<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\SAAS\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('saas::auth.login');
    }

    public function login(LoginRequest $request)
    {
        $userRequest = $request->validated();
        $user = User::where('email', $userRequest['email'])->first();
        if (!$user || !Hash::check($userRequest['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        Auth::guard()->login($user);

        return redirect()->to(route('saas.dashboard'))->with('success', 'Logged in!');
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(route('saas.welcome-page'))->with('error', 'Logged out!');
    }
}
