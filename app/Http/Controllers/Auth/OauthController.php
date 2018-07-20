<?php

namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OauthController extends Controller
{
    use AuthenticatesUsers;

    protected $redirect_uri;

    public function auth(Request $request)
    {
        return view('auth.oauth-login', ['client_id' => $request->get('client_id'), 'redirect_uri' => $request->get('redirect_uri')]);
    }

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (Auth::validate($request->only($this->username(), 'password'))) {
            // if users authenticated redirect back to client site
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'redirect_uri' => 'required|url',
            'client_id' => 'required|string',
        ]);

        $this->redirect_uri = $request->get('redirect_uri');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return resource
     */
    protected function sendLoginResponse(Request $request)
    {
        // generate token and save for authorizing in next requests
        $token = str_random(64);
        DB::table('users')->where('email', $request->get('email'))->update([
            'api_token' => $token,
        ]);

        // redirect to redirect_uri from client site
        return redirect($request->get('redirect_uri').'?token='.$token);
    }
}
