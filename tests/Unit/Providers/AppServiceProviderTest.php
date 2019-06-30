<?php

namespace Tests\Unit\Providers;

use App\Zaim;
use App\Zaim\Client as ZaimClient;
use GuzzleHttp\Client as HttpClient;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function testRegister()
    {
        $this->assertInstanceOf($class = HttpClient::class, resolve($class));
        $this->assertInstanceOf($class = ZaimClient::class, resolve($class));
        $this->assertInstanceOf($class = Zaim::class, resolve($class));
    }
}
