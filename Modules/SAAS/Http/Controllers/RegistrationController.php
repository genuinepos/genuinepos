<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\SAAS\Events\CustomerRegisteredEvent;
use Modules\SAAS\Http\Requests\RegistrationRequest;
use Modules\SAAS\Notifications\VerifyEmail;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('saas::auth.register');
    }

    public function register(RegistrationRequest $request)
    {
        $userRequest = $request->validated();
        $user = User::create([
            'name' => $userRequest['name'],
            'email' => $userRequest['email'],
            'password' => bcrypt($userRequest['password']),
        ]);

        $user->notify(new VerifyEmail);

        // event(new CustomerRegisteredEvent($user));
        return redirect()->to(route('saas.login'))->with('success', 'Successfully registered. Check your email and verify your account.');
    }
}
