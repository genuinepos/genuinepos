<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\BooleanType;
use App\Enums\UserActivityLogActionType;
use App\Enums\UserActivityLogSubjectType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use App\Services\Users\UserActivityLogService;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    public function __construct(private UserActivityLogService $userActivityLogService)
    {
        $this->middleware('guest')->except('logout');
    }

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


    public function showLoginForm()
    {
        if (Auth::check('web')) {

            return redirect()->back();
        }

        $businessInfo = DB::table('general_settings')
            ->whereIn('key', ['business_or_shop__business_name', 'business_or_shop__business_logo'])
            ->where('branch_id', null)->get()->pluck('value', 'key');

        return view('auth.login', compact('businessInfo'));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username_or_email' => 'required',
            'password' => 'required',
        ]);

        $user = User::with('branch', 'roles', 'roles.permissions')
            ->where('username', $request->username_or_email)
            ->orWhere('email', $request->username_or_email)->first();

        if (
            $user?->branch &&
            isset($user?->branch?->expire_date) &&
            date('Y-m-d') > $user?->branch?->expire_date &&
            !$role->hasPermissionTo('billing_renew_branch')
        ) {

            $msg = __('Login failed. Shop ') . ': ' . $user->branch->name . '/' . $user->branch->branch_code . ' ' . __('is expired. Please Contact your Authority.');
            session()->flash('errorMsg', $msg);
            return redirect()->back();
        }

        if (isset($user) && $user->allow_login == BooleanType::True->value) {

            if (
                Auth::attempt(['username' => $request->username_or_email, 'password' => $request->password]) ||
                Auth::attempt(['email' => $request->username_or_email, 'password' => $request->password])
            ) {
                if (!Session::has($user->language)) {

                    session(['lang' => $user->language]);
                }

                $this->userActivityLogService->addLog(action: UserActivityLogActionType::UserLogin->value, subjectType: UserActivityLogSubjectType::UserLogin->value, dataObj: $user, branchId: $user->branch_id, userId: $user->id);

                return redirect()->intended(route('dashboard.index'));
            } else {

                session()->flash('errorMsg', __('Sorry! Username/Email or Password not matched!'));
                return redirect()->back();
            }
        } else {

            session()->flash('errorMsg', __('Login failed. Please try with correct username/email and password'));
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        $this->userActivityLogService->addLog(action: UserActivityLogActionType::UserLogout->value, subjectType: UserActivityLogSubjectType::UserLogout->value, dataObj: auth()->user());

        if (auth()->user()->can('has_access_to_all_area')) {

            auth()->user()->branch_id = null;
            auth()->user()->is_belonging_an_area = BooleanType::False->value;
            auth()->user()->save();
        }

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
