<?php

namespace App\Http\Controllers;

use Spatie\Valuestore\Valuestore;

class PostController extends Controller
{
    /**
     * Controller qui gère la mise à jour des paramètres de sécurité du site via valuestore (un fichier json de config)
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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
