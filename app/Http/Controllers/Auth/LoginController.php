<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function redirectTo()
    {
        $role = auth()->user()->roles()->first();

        if ($role && $role->allowed_route != '') {
            return $this->redirectTo = $role->allowed_route ;
        }

        // Default redirection for users without an allowed_route
        return $this->redirectTo = '/frontend_user';
    }

    public function logout(Request $request)
    {
        $user = auth()->user(); // Retrieve the user before logging out
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        if ($user) {
            $role = $user->roles()->first();

            if ($role && $role->allowed_route != '') {
                return redirect($role->allowed_route . '/login');
            }
        }

        // Default redirection for users without an allowed_route
        return redirect('/frontend_user/login');
    }
    protected function loggedOut(Request $request)
    {
        Cache::forget('admin_side_menu');
        Cache::forget('role_routes');
        Cache::forget('user_routes');
    }
}
