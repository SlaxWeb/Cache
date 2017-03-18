<?php
/**
 * Cache Component Config
 *
 * Cache Component Configuration file
 *
 * @package   SlaxWeb\Cache
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.1
 */
/*
 * Cache handler
 *
 * The Cache component provides the following cache handlers:
 * - file
 * - memcached
 * - redis
 *
 * Each handler requires additional configuration further bellow.
 *
 * NOTE: at the moment only the 'file' handler is available.
 */
$configuration["handler"] = "file";

/*
 * Cache location
 *
 * The location depends on the handler. For 'file' handler it is the absolute path
 * to the directory where the Cache component may write to.
 */
$configuration["location"] = __DIR__ . "/../Cache";

/*
 * Maximum age
 *
 * Maximum age for data in the cache. This value is used as default for all data
 * stored in the cache, but can be overriden for each one specifically. Maximum
 * age is defined in seconds with a default value of int(3600), equivalent for one
 * hour.
 */
$configuration["maxAge"] = 3600;
