<?php
namespace SlaxWeb\Cache\Handler;

use SlaxWeb\Cache\Handler as AbstractHandler;

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
     *
     * @todo: check location permissions and throw exception
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function write(string $name, string $data): AbstractHandler
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $name): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name): string
    {
        return "";
    }
}
