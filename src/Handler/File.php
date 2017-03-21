<?php
namespace SlaxWeb\Cache\Handler;

use SlaxWeb\Cache\AbstractHandler;
use SlaxWeb\Cache\Exception\WriteException;
use SlaxWeb\Cache\Exception\CacheDataNotFoundException;
use SlaxWeb\Cache\Exception\CacheDataExpiredException;
use Slaxweb\Cache\Exception\CacheStoreNotWritableException;

/**
 * File Cache Handler
 *
 * The File Cache Handler writes the cache data to a file in the filesystem with
 * the cache data name as part of the file name.
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
class File extends AbstractHandler
{
    /**
     * Filesystem location
     *
     * @var string
     */
    protected $path = "";

    /**
     * Class constructor
     *
     * The File Cache handler requires the path of the cache location, where the
     * handler will store the cache data to. The constructor simply stores this
     * path to the protected property. The constructor also checks if the handler
     * can write to that location, and throws an exception if that is not the case.
     *
     * @param string $path Filesystem location
     * @param int $maxAge Default maximum age in seconds, default 0
     *
     * @throws \SlaxWeb\Cache\Exception\CacheStoreNotWritableException thrown if
     *     the cache filesystem location is not writable
     */
    public function __construct(string $path, int $maxAge = 0)
    {
        if (is_writable($path) === false) {
            throw new CacheStoreNotWritableException(
                "The cache filesystem location '{$path}' is not writable"
            );
        }
        $this->path = $path;
        $this->maxAge = $maxAge;
    }

    /**
     * @inheritDoc
     */
    public function write(string $name, string $data, int $maxAge = -1): AbstractHandler
    {
        if (file_put_contents(
                "{$this->path}{$name}.cache",
                $this->prepData($data, $maxAge)
            ) === false
        ) {
            throw new WriteException("Error writting data to cache.");
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $name): bool
    {
        return file_exists("{$this->path}{$name}.cache");
    }

    /**
     * @inheritDoc
     */
    public function get(string $name): string
    {
        if ($this->exists($name) === false) {
            throw new CacheDataNotFoundException(
                "The data you are trying to obtain does not exist."
            );
        }

        return $this->checkData(
            file_get_contents("{$this->path}{$name}.cache")
        )["data"];
    }

    /**
     * @inheritDoc
     */
    public function remove(string $name, bool $partial = false): bool
    {
        if ($partial) {
            return $this->removePartial($name);
        }

        if ($this->exists($name) === false) {
            throw new CacheDataNotFoundException(
                "The data you are trying to obtain does not exist."
            );
        }

        return unlink("{$this->path}{$name}.cache");
    }

    /**
     * Remove partial
     *
     * Removes all files containing '$name' from the cache location.
     *
     * @param string $name Name of the data stored in cache
     * @return bool
     */
    protected function removePartial($name): bool
    {
        $status = true;
        foreach (scandir($this->path) as $file) {
            if (pathinfo($this->path . $file, PATHINFO_EXTENSION) !== "cache") {
                continue;
            }
            if (strpos($file, $name) !== false) {
                $status = unlink($this->path . $file) && $status;
            }
        }
        return $status;
    }
}
