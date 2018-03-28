<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 22/03/18
 * Time: 9:52
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\Factory as Auth;
use Log;

class PassportCustomProvider extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $params = $request->all();

        if (array_key_exists('provider', $params)) {
            Config::set('auth.guards.api.provider', $params['provider']);
        } else {
            Config::set('auth.guards.api.provider', 'customers');
        }

        return $next($request);
    }
}