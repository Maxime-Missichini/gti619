<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Valuestore\Valuestore;

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
    protected $allowedAttempts = 5;
    protected $maxAttempts = 1; // Default is 5
    protected $decayMinutes = 2; // Default is 1
    protected $attempts = 0;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $valuestore = Valuestore::make('settings.json');
        $this->allowedAttempts = $valuestore['password_max_try'];
        $this->decayMinutes = $valuestore['password_attempt_delay'];
        $this->middleware('guest')->except('logout');
    }
}
