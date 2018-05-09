<?php

namespace Misfits\ApiGuard\Http\Controllers;

use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Manager;

/**
 * Class ApiGuardController
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Http\Controllers
 */
class ApiGuardController extends Controller
{
    /**
     * Variable for the application that is assigned to the response.
     *
     * @var Response $response
     */
    protected $response;

    /**
     * ApiGuardController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $fractal = new Manager();

        if (isset($_GET['include'])) {
            $fractal->parseIncludes($_GET['include']);
        }

        $this->response = new Response($fractal);
    }
}
