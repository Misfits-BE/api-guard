<?php

namespace Misfits\ApiGuard\Events;

use Illuminate\Queue\SerializesModels;
use Misfits\ApiGuard\Models\ApiKey;

/**
 * Class ApiKeyAuthenticated
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Events
 */
class ApiKeyAuthenticated
{
    use SerializesModels;

    /**
     * Public variable for the request data.
     *
     * @var mixed $request
     */
    public $request;

    /**
     * Public variable for the ApiKey instance.
     *
     * @var ApiKey $apiKey
     */
    public $apiKey;

    /**
     * Create a new event instance.
     *
     * @param mixed  $request   Public variable for the request data
     * @param ApiKey $apiKey    Public variable for the ApiKey instance.
     */
    public function __construct($request, ApiKey $apiKey)
    {
        $this->request = $request;
        $this->apiKey = $apiKey;
    }
}
