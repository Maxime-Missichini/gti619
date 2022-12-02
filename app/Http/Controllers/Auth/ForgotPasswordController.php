<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Spatie\Valuestore\Valuestore;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        $valuestore = Valuestore::make('settings.json');
        if ($valuestore->get('password_reset','true') == 'true') {
            return view('auth.passwords.email');
        }else{
            return redirect('home')->withErrors('Password reset not permitted');
        }
    }
}
