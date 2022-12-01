<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaddyController extends Controller
{
    public function check(Request $request)
    {
        $authorizedDomains = [
            'localhost',
            // Add subdomains here
        ];
        error_log($request);

        if (in_array($request->query('domain'), $authorizedDomains)) {
            return response('Domain Authorized');
        }

        // Abort if there's no 200 response returned above
        abort(503);
    }
}
