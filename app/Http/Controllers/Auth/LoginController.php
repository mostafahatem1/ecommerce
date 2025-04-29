<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        logout as protected originalLogout;
    }

    /**
     * Default route after successful login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Define username used for authentication.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Override redirect to specify custom route based on user role after login.
     *
     * @return string
     */
    public function redirectTo()
    {
        $roleRoute = $this->getAllowedRouteByRole();

        // Default redirection for users without a defined allowed_route
        return $roleRoute ?: '/frontend_user';
    }

    /**
     * Logout user while preserving cart session, handling exceptions gracefully.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        $cart = collect($request->session()->get('cart'));

        try {
            // Logout User
            $this->originalLogout($request);
            // Invalidate current session data and regenerate token (critical for security/session fixes)
            $request->session()->invalidate();
            $request->session()->regenerateToken();


            // Restore cart if 'destroy_on_logout' is false
            if (!config('cart.destroy_on_logout', false)) {
                $this->restoreCartSession($cart, $request);
            }

            // Perform cache clearance actions
            $this->performCacheCleanup();

            // Redirect based on role after logout
            return $this->determineLogoutRedirect($user);

        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());

            return redirect('/frontend_user/login')
                ->with('error', 'حدث خطأ أثناء تسجيل الخروج');
        }
    }

    /**
     * Restores cart items in session.
     *
     * @param \Illuminate\Support\Collection $cart
     * @param Request $request
     * @return void
     */
    private function restoreCartSession($cart, Request $request)
    {
        $cart->each(function ($items, $identifier) use ($request) {
            $request->session()->put('cart.' . $identifier, $items);
        });
    }

    /**
     * Determines redirect URL based on user role after logout.
     *
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function determineLogoutRedirect($user)
    {
        $roleRoute = $this->getAllowedRouteByRole($user);

        return redirect($roleRoute ? $roleRoute . '/login' : '/frontend_user/login');
    }

    /**
     * Performs necessary cache cleaning upon logout.
     *
     * @return void
     */
    protected function performCacheCleanup()
    {
        Cache::forget('admin_side_menu');
        Cache::forget('role_routes');
        Cache::forget('user_routes');
    }

    /**
     * Retrieves allowed route based on user's first role.
     *
     * @param null $user
     * @return string|null
     */
    private function getAllowedRouteByRole($user = null)
    {
        $user = $user ?: auth()->user();
        $role = $user->roles()->first();

        return $role->allowed_route ?? null;
    }
}
