<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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


    public function login(Request $request)
    {
        $userId = DB::table('users')->select('id')->where('email', $request->email)->first()->id;
        $attempts = DB::table('users_login')->select('attempts')
            ->where('user_id', $userId)->first();
        if($attempts == null){
            DB::table('users_login')->insert(
                ['user_id' => $userId,
                'attempts' => 1]);
            $attempts = DB::table('users_login')->select('attempts')
                ->where('user_id', $userId)->first()->attempts;
        }else{
            $attempts = $attempts->attempts;
        }

        if($attempts < $this->allowedAttempts) {
            $this->validateLogin($request);

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }
                DB::table('users_login')->where('user_id',$userId)->delete();
                Log::info('User with id '.$userId.' successfully logged in');
                return $this->sendLoginResponse($request);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            Log::info('User with id '.$userId.' failed to login');
            $this->incrementLoginAttempts($request);
            $attempts++;
            DB::table('users_login')->update(['user_id' => $userId, 'attempts' => $attempts]);

            return $this->sendFailedLoginResponse($request);
        }else{
            return throw ValidationException::withMessages([
                    $this->username() => [trans('auth.blocked')],
            ]);
        }
    }
}
