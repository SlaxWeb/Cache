<?php
namespace SlaxWeb\Cache\Exception;

/**
 * Cache Data Invalid Exception
 *
 * Thrown if the data in the case is in any way malformed, and can't be unserialized
 * or the unserialized data does not yield the expected array. It provides an extra
 * method to obtain the serialized data that caused the exception.
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
class CacheDataInvalidException extends \Exception
{
    /**
     * Serialized data
     *
     * @var string
     */
    protected $serialized = "";

    /**
     * Constructor
     *
     * Overriden constructor to accept serialized data as second parameter.
     *
     * @param string $message Exception message
     * @param string $serialized Serialized data that caused the exception
     * @param int $code Error code, default 0
     * @param \Exception $previous Previous exception, default null
     */
    public function __construct(
        string $message,
        string $serialized,
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->serialized = $serialized;
        parent::__construct($message, $code, $previous);
    }
}
