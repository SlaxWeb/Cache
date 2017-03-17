<?php
namespace SlaxWeb\Cache\Exception;

/**
 * Cache Data Expired Exception
 *
 * Thrown if the data has expired and is no longer valid. The exception also provides
 * a method to retrieve the unserialized data that caused the exception.
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
class CacheDataExpiredException extends \Exception
{
    /**
     * Unserialized cached data
     *
     * @var array
     */
    protected $data = "";

    /**
     * Constructor
     *
     * Overriden constructor to accept unserialized data as second parameter.
     *
     * @param string $message Exception message
     * @param string $serialized Serialized data that caused the exception
     * @param int $code Error code, default 0
     * @param \Exception $previous Previous exception, default null
     */
    public function __construct(
        string $message,
        array $data,
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
}
