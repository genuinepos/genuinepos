<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\RoleType;
use App\Enums\BooleanType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Utils\UserActivityLogUtil;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected $userActivityLogUtil;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check('web')) {
            return redirect()->back();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->where('allow_login', 1)->first();
        if (isset($user) && $user->allow_login == 1) {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

                if (!Session::has($user->language)) {

                    session(['lang' => $user->language]);
                }

                $this->userActivityLogUtil->addLog(action: 4, subject_type: 18, data_obj: $user, branch_id: $user->branch_id,  user_id: $user->id);

                if ($user->role_type == RoleType::SuperAdmin->value || $user->role_type == RoleType::Admin->value) {

                    $user->branch_id = null;
                    $user->is_belonging_an_area = BooleanType::False->value;
                    $user->save();
                }

                return redirect()->intended(route('dashboard.index'));
            } else {
                session()->flash('errorMsg', __('Sorry! Username or Password not matched!'));
                return redirect()->back();
            }
        } else {
            session()->flash('errorMsg', __('Login failed. Please try with correct username and password'));
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        $this->userActivityLogUtil->addLog(action: 5, subject_type: 19, data_obj: auth()->user());

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
