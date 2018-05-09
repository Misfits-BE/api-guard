<?php

namespace Misfits\ApiGuard\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Misfits\ApiGuard\Console\Commands\GenerateApiKey;
use Misfits\ApiGuard\Http\Middleware\AuthenticateApiKey;

/**
 * Class ApiGuardServiceProvider
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Providers
 */
class ApiGuardServiceProvider extends ServiceProvider
{
    /**
     * The applied middleware instances from the package.
     *
     * @var array $middlewares
     */
    protected $middlewares = ['auth.apikey' => AuthenticateApiKey::class];

    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        // Publish migrations
        $this->publishFiles();

        $this->defineMiddleware($router);
    }

    /** /
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([GenerateApiKey::class,]);
    }

    /**
     * Define the middlewares from the package.
     *
     * @param  string $router The variable from the routing system.
     * @return void
     */
    private function defineMiddleware($router)
    {
        foreach ($this->middlewares as $name => $class) {
            if (version_compare(app()->version(), '5.4.0') >= 0) {
                $router->aliasMiddleware($name, $class);
            } else {
                $router->middleware($name, $class);
            }
        }
    }

    /**
     * The class that handles all the file publish methods.
     *
     * @return void
     */
    private function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../../config/apiguard.php' => config_path('apiguard.php'),
        ], 'config');
    }
}
