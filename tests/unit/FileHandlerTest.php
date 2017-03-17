<?php
namespace SlaxWeb\Cache\Test\Unit;

use Mockery as m;

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
