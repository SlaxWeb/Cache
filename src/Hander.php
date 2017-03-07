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
abstract class Handler
{
    /**
     * Write
     *
     * Write data to cache with the given name and data. The data must be in the
     * string format.
     *
     * @param string $name Data name
     * @param string $data Data to cache
     * @return self
     */
    abstract public function write(string $name, string $data): Handler;

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
     * @exceptions \SlaxWeb\Cache\Exception\CachedDataNotFoundException
     */
    abstract public function get(string $name): string;
}
