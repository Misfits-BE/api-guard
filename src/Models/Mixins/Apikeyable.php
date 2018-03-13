<?php

namespace Misfits\ApiGuard\Models\Mixins;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Misfits\ApiGuard\Models\ApiKey;

/**
 * Class ApiKeyable
 * ----
 * Trait class for the apikey model functions. 
 * 
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Models\Mixins
 */
trait Apikeyable
{
    /**
     * Method for the morphMany relation.
     * 
     * @return MorpMany
     */
    public function apiKeys(): MorpMany
    {
        return $this->morphMany(config('apiguard.models.api_key', ApiKey::class), 'apikeyable');
    }

    /**
     * Method for creating a new api key in the database 
     * 
     * @return ApiKey
     */
    public function createApiKey(): ApiKey
    {
        return ApiKey::make($this);
    }
}
