<?php
namespace SlaxWeb\Cache\Service;

use Pimple\Container;

/**
 * Cache Component Service Provider
 *
 * Registers the manager and handler services to the service container.
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
class Provider implements \Pimple\ServiceProviderInterface
{
    /**
     * Register services
     *
     * Called when the container is about to register this provider. It defines
     * all the required services for the Cache component.
     *
     * @param \Pimple\Container $container Service Container
     * @return void
     */
    public function register(Container $app)
    {
        $app["cache.service"] = function(Container $app) {
            $handlerClass = ucfirst($app["config.service"]["cache.handler"]);

            return new \SlaxWeb\Cache\Manager(
                $app["cache{$handlerClass}Handler.service"]
            );
        };

        $app["cacheFileHandler.service"] = function(Container $app) {
            return new \SlaxWeb\Cache\Handler\File(
                $app["config.service"]["cache.location"],
                $app["config.service"]["cache.maxAge"]
            );
        };
    }
}
