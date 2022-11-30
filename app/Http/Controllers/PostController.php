<?php

namespace App\Http\Controllers;

use Spatie\Valuestore\Valuestore;

class PostController extends Controller
{
    public function __invoke()
    {
        $valuestore = Valuestore::make('settings.json');
        $valuestore['password_minimum_length'] = request('length');
        $valuestore['password_reset'] = request('reset');
        $valuestore['password_characters_allowed'] = request('allowed');
        $valuestore['password_reusable'] = request('reusable');
        $valuestore['password_attempt_delay'] = request('delay');
        $valuestore['password_max_try'] = request('try');

        return redirect('admin');
    }
}
