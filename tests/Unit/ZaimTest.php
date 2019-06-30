<?php

namespace Tests\Unit;

use App\Entities\Payment;
use App\Zaim;
use App\Zaim\Client;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Tests\TestCase;

class ZaimTest extends TestCase
{
    /**
     * @test
     */
    public function testConstruct()
    {
        $zaim = new Zaim(resolve(Client::class));

        $this->assertInstanceOf(Zaim::class, $zaim);
    }

    /**
     * @test
     */
    public function testLogin()
    {
        // Mock zaim client.
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('getLogin')->andReturn(
            new Response(200, [], file_get_contents(
                base_path('tests/stubs/login.html')
            ))
        );
        $client->shouldReceive('postLogin')->andReturn(
            new Response(200, [], file_get_contents(
                base_path('tests/stubs/logged-in.html')
            ))
        );
        $client->shouldReceive('continue')->andReturn(
            new Response(200)
        );

        $instance = new Zaim($client);

        $this->assertEquals($instance, $instance->login('email', 'password'));
    }

    /**
     * @test
     */
    public function testGetPayments()
    {
        // Mock zaim client.
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('getMoney')->andReturn(
            new Response(200, [], file_get_contents(
                base_path('tests/stubs/money.html')
            ))
        );

        $instance = new Zaim($client);

        $payments = $instance->getPayments($month = Carbon::today());

        $this->assertIsArray($payments);
        $this->assertContainsOnlyInstancesOf(Payment::class, $payments);
    }
}
