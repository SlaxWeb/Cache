<?php
namespace SlaxWeb\Cache;

use SlaxWeb\Cache\Exception\WriteException;

/**
 * Cache Manager
 *
 * The Cache Manager class manages writes and reads from the cache through a cache
 * handler on which it depends.
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
class Manager
{
    /**
     * Cache handler
     *
     * @var \SlaxWeb\Cache\AbstractHandler
     */
    protected $handler = null;

    /**
     * Constructor
     *
     * Class constructor copies the dependencies to the class properties. The Cache
     * Manager depends on the cache handler object, and uses it throughout its execution
     * to write and read from the cache.
     *
     * @param \SlaxWeb\Cache\AbstractHandler $handler Cache handler object
     */
    public function __construct(AbstractHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Write
     *
     * Serializes the data and writes it to cache using the handler. It accepts
     * the name for the cached data as the first parameter, the data as the second
     * parameter, and a maximum age value as the third parameter. If the maximum
     * age is not supplied or is of a negative value, the configured default is
     * used. If an error occurs when trying to write to cahce, bool(false) is returned,
     * bool(true) otherwise.
     *
     * @param string $name Name of the data to be written to cache
     * @param mixed $data Data to be stored into the cache
     * @param int $maxAge Maximum age for this specific data, default -1
     * @return bool
     */
    public function write(string $name, $data, int $maxAge = -1): bool
    {
        try {
            $this->handler->write($name, serialize($data), $maxAge);
        } catch (WriteException $e) {
            return false;
        }
        return true;
    }

    /**
     * Read
     *
     * Reads the data from the cache and returns. If the data has expired, it will
     * automatically remove the expired data from the cache. It does not handle
     * any handler thrown exceptions, and simply returns that what it receives.
     *
     * @param string $name Name of the data stored in cache
     * @return mixed
     */
    public function read(string $name)
    {
        try {
            return unserialize($this->handler->get($name));
        } catch (Exception\CacheException $e) {
            if (!($e instanceof Exception\CacheDataNotFoundException)) {
                $this->handler->remove($name);
            }
            throw $e;
        }
    }

    /**
     * Remove
     *
     * Removes the data from the cache. No exceptions are handled that are thrown
     * in the handler. It accepts the name of the cached data. If the second parameter
     * is set to true, all cached data containing that name will be removed.
     *
     * @param string $name Name of the data stored in cache
     * @param bool $partial Remove all data containing '$name', default false
     * @return bool
     */
    public function remove(string $name, bool $partial = false): bool
    {
        return $this->handler->remove($name, $partial);
    }
}
