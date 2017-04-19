<?php
namespace SlaxWeb\Cache\Test\Unit;

use Mockery as m;
use SlaxWeb\Cache\Exception\CacheDataInvalidException;
use SlaxWeb\Cache\Exception\CacheDataExpiredException;
use SlaxWeb\Cache\Exception\CacheDataNotFoundException;

class FileHandlerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $handler = null;
    protected $cachePath = __DIR__ . "/../_support/";
    protected $cacheFile = "testCache";

    public function testWrite()
    {
        $this->handler->write($this->cacheFile, "test cache data", 300);

        $this->assertFileExists("{$this->cachePath}{$this->cacheFile}.cache");

        $cached = unserialize(
            file_get_contents("{$this->cachePath}{$this->cacheFile}.cache")
        );

        $this->assertInternalType(gettype($cached), [], "Cached data not an array as expected");

        $this->assertArrayHasKey("timestamp", $cached);
        $this->assertArrayHasKey("maxage", $cached);
        $this->assertArrayHasKey("data", $cached);

        $this->assertEquals(300, $cached["maxage"]);
        $this->assertEquals("test cache data", $cached["data"]);
    }

    public function testGet()
    {
        $exception = false;
        try {
            $this->handler->get($this->cacheFile);
        } catch (CacheDataNotFoundException $e) {
            $exception = true;
        }
        $this->assertTrue($exception, "Expected a cache not found exception");

        file_put_contents(
            "{$this->cachePath}{$this->cacheFile}.cache",
            serialize(["invalid"])
        );
        $exception = false;
        try {
            $this->handler->get($this->cacheFile);
        } catch (CacheDataInvalidException $e) {
            $exception = true;
        }

        file_put_contents(
            "{$this->cachePath}{$this->cacheFile}.cache",
            serialize([
                "timestamp" => 1,
                "maxage"    => 10,
                "data"      => "cached data"
            ])
        );
        $exception = false;
        try {
            $this->handler->get($this->cacheFile);
        } catch (CacheDataExpiredException $e) {
            $exception = true;
            $this->assertFalse(
                file_exists("{$this->cachePath}{$this->cacheFile}.cache"),
                "Expired cache data was not automatically removed"
            );
        }

        file_put_contents(
            "{$this->cachePath}{$this->cacheFile}.cache",
            serialize([
                "timestamp" => time(),
                "maxage"    => 600,
                "data"      => "cached data"
            ])
        );
        $this->assertEquals("cached data", $this->handler->get($this->cacheFile));
    }

    public function testGetNoMaxAge()
    {
        file_put_contents(
            "{$this->cachePath}{$this->cacheFile}.cache",
            serialize([
                "timestamp" => 1,
                "maxage"    => 0,
                "data"      => "cached data"
            ])
        );
        $this->assertEquals("cached data", $this->handler->get($this->cacheFile));
    }

    public function testRemove()
    {
        file_put_contents(
            "{$this->cachePath}{$this->cacheFile}.cache",
            serialize([
                "timestamp" => 1,
                "maxage"    => 0,
                "data"      => "cached data"
            ])
        );
        $this->assertTrue($this->handler->remove($this->cacheFile));
    }

    public function testPartialRemove()
    {
        system("touch {$this->cachePath}/partial_cache_file_1.cache");
        system("touch {$this->cachePath}/partial_cache_file_2.cache");
        $this->handler->remove("partial", true);
        $this->assertFalse(file_exists("{$this->cachePath}/partial_cache_file_1.cache"));
        $this->assertFalse(file_exists("{$this->cachePath}/partial_cache_file_2.cache"));
    }

    protected function _before()
    {
        if (file_exists("{$this->cachePath}{$this->cacheFile}.cache")) {
            unlink("{$this->cachePath}{$this->cacheFile}.cache");
        }

        $this->handler = new \SlaxWeb\Cache\Handler\File(
            __DIR__ . "/../_support/",
            600
        );
    }

    protected function _after()
    {
        if (file_exists("{$this->cachePath}{$this->cacheFile}.cache")) {
            unlink("{$this->cachePath}{$this->cacheFile}.cache");
        }

        m::close();
    }
}
