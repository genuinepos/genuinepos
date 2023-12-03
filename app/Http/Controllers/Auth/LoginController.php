<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utils\UserActivityLogUtil;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

        $admin = User::where('username', $request->username)->where('allow_login', 1)->first();

        if (isset($admin) && $admin->allow_login == 1) {

            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

                if (!Session::has($admin->language)) {

                    session(['lang' => $admin->language]);
                }

                $this->userActivityLogUtil->addLog(action: 4, subject_type: 18, data_obj: $admin, branch_id: $admin->branch_id,  user_id: $admin->id);

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
