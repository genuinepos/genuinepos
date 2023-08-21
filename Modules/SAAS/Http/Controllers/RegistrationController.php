<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\SAAS\Events\TenantRegistered;
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
        $user = User::create([
            'name' => $userRequest['name'],
            'email' => $userRequest['email'],
            'password' => bcrypt($userRequest['password']),
        ]);

        // Auth::guard()->login($user);
        // event(new TenantRegistered($user));
        return redirect()->to(route('saas.login'))->with('success', 'Successfully Registered. You can login!');
    }
}
