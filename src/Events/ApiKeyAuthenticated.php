<?php

namespace Misfits\ApiGuard\Events;

use Illuminate\Queue\SerializesModels;
use Misfits\ApiGuard\Models\ApiKey;

class ApiKeyAuthenticated
{
    use SerializesModels;

    public $request;

    public $apiKey;

    /**
     * Create a new event instance.
     *
     * @param $request
     * @param ApiKey $apiKey
     */
    public function __construct($request, ApiKey $apiKey)
    {
        $this->request = $request;
        $this->apiKey = $apiKey;
    }
}