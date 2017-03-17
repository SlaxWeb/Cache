<?php
namespace SlaxWeb\Cache;

/**
 * Cache Handler
 *
 * This abstract Cache Handler class defines functionality for handling data and
 * defines abstract methods for functionality that each specific handler must implement.
 * All cache handlers must extend from this abstract class.
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
abstract class AbstractHandler
{
    /**
     * Maximum cache age
     *
     * @var int
     */
    protected $maxAge = 0;

    /**
     * Write
     *
     * Write data to cache with the given name and data. The data must be in the
     * string format.
     *
     * @param string $name Data name
     * @param string $data Data to cache
     * @param int $maxAge Maximum age in seconds, default -1
     * @return self
     *
     * @throws \SlaxWeb\Cache\Exception\WriteException if an error occurs writting
     *     to cache
     */
    abstract public function write(
        string $name,
        string $data,
        int $maxAge = -1
    ): AbstractHandler;

    /**
     * Data exists
     *
     * Checks if the data exists in the cache and retuns a bool value.
     *
     * @param string $name Data name
     * @return bool
     */
    abstract public function exists(string $name): bool;

    /**
     * Get data
     *
     * Gets the data from the cache based on the received name. If data is not found
     * a 'CachedDataNotFoundException' is thrown.
     *
     * @param string $name Data name
     * @return string
     *
     * @throws \SlaxWeb\Cache\Exception\CachedDataNotFoundException
     */
    abstract public function get(string $name): string;

    /**
     * Prepare data
     *
     * Prepares the data for writting into cache. Adds the required timestamps,
     * the actual data, and max age, and returns it all as a serialized array. If 
     * he method retrieves a maximum age value as second parameter it will use it
     * instead of the default.
     *
     * @param string $data User data for caching
     * @param int $maxAge Maximum age in seconds, default -1
     * @return string
     */
    protected function prepData(string $data, int $maxAge = -1): string
    {
        return serialize([
            "timestamp" =>  time(),
            "maxage"    =>  $maxAge >= 0 ? $maxAge : $this->maxAge,
            "data"      =>  $data
        ]);
    }
}
