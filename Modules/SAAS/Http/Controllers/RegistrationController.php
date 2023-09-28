<?php

namespace Modules\SAAS\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Modules\SAAS\Events\CustomerRegisteredEvent;
use Modules\SAAS\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('saas::auth.register');
    }

    public function register(RegistrationRequest $request)
    {
        $userRequest = $request->validated();
        // $user = User::create([
        //     'name' => $userRequest['name'],
        //     'email' => $userRequest['email'],
        //     'password' => bcrypt($userRequest['password']),
        // ]);

        $user = User::skip(2)->first();
        // event(new Registered($user));
        // return back()->with('success', 'Successfully registered. Check your email and verify your account.');
        event(new CustomerRegisteredEvent($user));
        return redirect()->to(route('saas.login'))->with('success', 'Successfully registered. Check your email and verify your account.');
    }
}
