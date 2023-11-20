<?php

namespace Modules\SAAS\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\SAAS\Utils\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Modules\SAAS\Http\Requests\LoginRequest;
use Illuminate\Validation\ValidationException;

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
        if (! $user || ! Hash::check($userRequest['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        // Impersonate user logic
        Auth::guard()->login($user);
        if(isset($user->primary_tenant_id)) {
            $redirectUrl = '/home';
            $tenant = \App\Models\Tenant::find($user->primary_tenant_id);
            $token = tenancy()->impersonate($tenant, $user->id, $redirectUrl);
            $domain = UrlGenerator::generateFullUrlFromDomain($tenant->domains()?->first()?->domain);
            if(isset($token) && isset($domain)) {
                return redirect("$domain/impersonate/{$token->token}");
            }
        }
        return Redirect::intended(Redirect::getIntendedUrl())->with('success', 'Logged in!');
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(route('saas.welcome-page'));
    }
}
