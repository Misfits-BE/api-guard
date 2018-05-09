<?php

namespace Misfits\ApiGuard\Http\Requests;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use League\Fractal\Manager;

/**
 * Class ApiGuardFormRequest
 * -----
 * Base class for the API endpoint validation.
 * Applied on PUT, POST, POST, PATCH methods.
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Http\Requests
 */
class ApiGuardFormRequest extends FormRequest
{
    /**
     * Function to determine if the validation instance expect to return some JSON response.
     *
     * @return bool
     */
    public function expectsJson()
    {
        return true;
    }

    /**
     * Format the errors from the given Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        return $validator->getMessageBag()->toArray();
    }

    /**
     * Return the JSON formatted response with the validation errors.
     *
     * @param  array $errors The error bag with that contains the validation errors.
     * @return mixed
     */
    public function response(array $errors)
    {
        $response = new Response(new Manager());
        return $response->errorUnprocessable($errors);
    }
}
