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

    /**
     * Le nombre de tentative maximum avant blocage du compte
     * @var int|mixed
     */
    protected $allowedAttempts = 5;

    /**
     * Nombre de tentatives avant attente d'une durée de decayMinutes avant la prochaine tentative
     * @var int
     */
    protected $maxAttempts = 1; // Default is 5
    protected $decayMinutes = 2; // Default is 1

    /**
     * Create a new controller instance.
     * Ici on extrait les paramètres de notre configuration
     * @return void
     */
    public function __construct()
    {
        $valuestore = Valuestore::make('settings.json');
        $this->allowedAttempts = $valuestore['password_max_try'];
        $this->decayMinutes = $valuestore['password_attempt_delay'];
        $this->middleware('guest')->except('logout');
    }

    /**
     * Compare la réponse de l'utilisateur au challenge et sa grid card
     * @param $response
     * @param $userId
     * @param $challenge
     * @return bool
     */
    public function checkGridCard($response, $userId, $challenge)
    {
        $gridCard = DB::table('users')->select('grid_card')->where('id', $userId)
            ->first()->grid_card;
        $arrayGrid = str_split($gridCard);
        $arrayResponse = str_split($response);
        $arrayChallenge = explode(';', $challenge);
        $counter = 0;
        foreach($arrayChallenge as $pos){
            if($arrayResponse[$counter] != $arrayGrid[$pos-1]){
                return false;
            }
            $counter++;
        }
        return true;
    }

    /**
     * Override le login par défaut avec le contrôle de la grid card et la trace des tentatives de login
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $userId = DB::table('users')->select('id')->where('email', $request->email);

        //Si l'email n'est pas trouvé dans la base de donnée
        if($userId->first() == null){
            return $this->sendFailedLoginResponse($request);
        }

        $userId = $userId->first()->id;

        //Comptage manuel du nombre de tentatives (on met à 1 si n'existait pas)
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

        //Si l'utilisateur n'a pas atteint son maximum on continue
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

            if(!$this->checkGridCard($request->challenge, $userId, $request->question)) {

                // If the login attempt was unsuccessful we will increment the number of attempts
                // to login and redirect the user back to the login form. Of course, when this
                // user surpasses their maximum number of attempts they will get locked out.
                Log::info('User with id '.$userId.' failed to login');
                $this->incrementLoginAttempts($request);
                $attempts++;
                DB::table('users_login')->where('user_id', $userId)->update(['attempts' => $attempts]);

                return throw ValidationException::withMessages([
                    $this->username() => [trans('auth.challenge')],
                ]);
            }

            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }
                DB::table('users_login')->where('user_id', $userId)->delete();
                Log::info('User with id ' . $userId . ' successfully logged in');
                return $this->sendLoginResponse($request);
            }


            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            Log::info('User with id '.$userId.' failed to login');
            $this->incrementLoginAttempts($request);
            $attempts++;
            DB::table('users_login')->where('user_id', $userId)->update(['attempts' => $attempts]);

            return $this->sendFailedLoginResponse($request);
        }
        //Sinon on bloque l'utilisateur avec un message d'erreur custom
        else{
            return throw ValidationException::withMessages([
                    $this->username() => [trans('auth.blocked')],
            ]);
        }
    }
}
