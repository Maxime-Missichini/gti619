<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\PasswordRule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Spatie\Valuestore\Valuestore;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Reset the given user's password.
     *
     * @param  CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $userEmail = $user->getEmailForPasswordReset();
        $userId = DB::table('users')->select('id')->where('email', $userEmail)->first()->id;
        $userPass = DB::table('users')->select('password')->where('id', $userId)->first()->password;

        DB::table('user_passwords')->insert([
            'user_id' => $userId,
            'password' => $userPass
        ]);

        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        Log::info('User with id '.$userId.' changed his password');

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules(Request $request)
    {
        $valuestore = Valuestore::make('settings.json');
        if($valuestore->get('password_characters_allowed','all') == 'alphanumeric') {
            return [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed',
                    Password::min($valuestore->get('password_minimum_length', 8))
                    ->letters()->numbers(), new PasswordRule($request->email)],
            ];
        }elseif($valuestore->get('password_characters_allowed','all') == 'all'){
            return [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed',
                    Password::min($valuestore->get('password_minimum_length', 8))
                    ->mixedCase()->letters()->numbers()->symbols(), new PasswordRule($request->email)],
            ];
        }else{
            return [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed',
                    Password::min($valuestore->get('password_minimum_length', 8))
                    ->mixedCase()->letters()->numbers(), new PasswordRule($request->email)],
            ];
        }
    }

    public function reset(Request $request)
    {
        $request->validate($this->rules($request), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    public function updateLogged(Request $request)
    {
        $valuestore = Valuestore::make('settings.json');
        if($valuestore->get('password_characters_allowed','all') == 'alphanumeric') {
            $request->validate([
                'old_password' => 'required',
                'password' => ['required', 'confirmed',
                    Password::min($valuestore->get('password_minimum_length', 8))
                        ->letters()->numbers(), new PasswordRule($request->email)],
            ], $this->validationErrorMessages());
        }elseif($valuestore->get('password_characters_allowed','all') == 'all'){
            $request->validate([
                'old_password' => 'required',
                'password' => ['required', 'confirmed',
                    Password::min($valuestore->get('password_minimum_length', 8))
                        ->mixedCase()->letters()->numbers()->symbols(), new PasswordRule($request->email)],
            ], $this->validationErrorMessages());
        }else{
            $request->validate([
                'old_password' => 'required',
                'password' => ['required', 'confirmed',
                    Password::min($valuestore->get('password_minimum_length', 8))
                        ->mixedCase()->letters()->numbers(), new PasswordRule($request->email)],
            ], $this->validationErrorMessages());
        }

        $userPassword = DB::table('users')->select('password')->where('email', $request->email)
            ->first()->password;

        if (!Hash::check($request->old_password, $userPassword)) {
            $response = \Illuminate\Support\Facades\Password::INVALID_TOKEN;
            return $this->sendResetFailedResponse($request, $response);
        }

        $user = User::where('email', $request->email)->first();

        $this->resetPassword($user, $request->password);

        $response = \Illuminate\Support\Facades\Password::PASSWORD_RESET;

        return $this->sendResetResponse($request, $response);
    }
}
