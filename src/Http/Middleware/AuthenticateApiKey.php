<?php

namespace Misfits\ApiGuard\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Misfits\ApiGuard\Events\ApiKeyAuthenticated;

/**
 * Class AuthenticateApiKey
 * ----
 * Middleware to protect the API endpoints.
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Http\Middleware
 */
class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request    The variable for all the data that is related to the request.
     * @param  Closure                  $next       Variable for further processing the request
     * @param  string|null              $guard      The authentication guard name
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $apiKeyValue = $request->header(config('apiguard.header_key', 'X-Authorization'));

        $apiKey = app(config('apiguard.models.api_key', 'Chrisbjr\ApiGuard\Models\ApiKey'))->where('key', $apiKeyValue)
            ->first();

        if (empty($apiKey)) {
            return $this->unauthorizedResponse();
        }

        // Update this api key's last_used_at and last_ip_address
        $apiKey->update([
            'last_used_at'    => Carbon::now(),
            'last_ip_address' => $request->ip(),
        ]);

        $apikeyable = $apiKey->apikeyable;

        // Bind the user or object to the request
        // By doing this, we can now get the specified user through the request object in the controller using:
        // $request->user()
        $request->setUserResolver(function () use ($apikeyable) {
            return $apikeyable;
        });

        // Attach the apikey object to the request
        $request->apiKey = $apiKey;

        event(new ApiKeyAuthenticated($request, $apiKey));

        return $next($request);
    }

    /**
     * The response when the user gives a wrong API key.
     *
     * @return mixed
     */
    protected function unauthorizedResponse()
    {
        return response([
            'error' => [
                'code'      => '401',
                'http_code' => 'GEN-UNAUTHORIZED',
                'message'   => 'Unauthorized.',
            ],
        ], 401);
    }
}
