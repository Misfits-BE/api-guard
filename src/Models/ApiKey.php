<?php

namespace Misfits\ApiGuard\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;

/**
 * Class ApiKey
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Models
 */
class ApiKey extends Model
{
    use SoftDeletes;

    /**
     * Mass-assign fields for the database table.
     *
     * @var array
     */
    protected $fillable = ['key', 'service', 'apikeyable_id', 'apikeyable_type', 'last_ip_address', 'last_used_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_used_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The morph relation for getting the data for the api key.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function apikeyable()
    {
        return $this->morphTo();
    }

    /**
     * Create the new api key in the database table. (MySQL: api_keys)
     *
     * @param  mixed $apikeyable The api key instance form the generate method
     * @return ApiKey
     */
    public static function make($apikeyable)
    {
        $apiKey = new ApiKey([
            'key'             => self::generateKey(),
            'apikeyable_id'   => $apikeyable->getKey(),
            'apikeyable_type' => get_class($apikeyable),
            'last_ip_address' => Request::ip(),
            'last_used_at'    => Carbon::now(),
        ]);

        $apiKey->save();

        return $apiKey;
    }

    /**
     * A sure method to generate a unique API key
     *
     * @return string
     */
    public static function generateKey()
    {
        do {
            $salt = sha1(time() . mt_rand());
            $newKey = substr($salt, 0, 40);
        } // Already in the DB? Fail. Try again
        while (self::keyExists($newKey));

        return $newKey;
    }

    /**
     * Checks whether a key exists in the database or not
     *
     * @param  string $key The api key from the database table.
     * @return bool
     */
    private static function keyExists($key): bool
    {
        $apiKeyCount = self::where('key', '=', $key)->limit(1)->count();

        if ($apiKeyCount > 0) return true;

        return false;
    }
}
