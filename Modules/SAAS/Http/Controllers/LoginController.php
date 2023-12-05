<?php

namespace Modules\SAAS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Modules\SAAS\Http\Requests\LoginRequest;
use Modules\SAAS\Utils\UrlGenerator;

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

        if (isset($user->primary_tenant_id)) {
            $redirectUrl = RouteServiceProvider::HOME;
            $tenant = \App\Models\Tenant::find($user->primary_tenant_id);
            $domain = UrlGenerator::generateFullUrlFromDomain($tenant->domains()?->first()?->domain);
            $token = tenancy()->impersonate($tenant, $tenant->impersonate_user, $redirectUrl);
            if (isset($token) && isset($domain)) {
                return redirect("$domain/impersonate/{$token->token}");
            }
        } else {
            Auth::guard()->login($user);

            return Redirect::intended(Redirect::getIntendedUrl())->with('success', 'Logged in!');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(route('saas.welcome-page'));
    }
}
