<?php

namespace Tests\Unit\Zaim;

use App\Zaim\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function testConstruct()
    {
        $instance = new Client(new HttpClient);

        $this->assertInstanceOf(Client::class, $instance);
    }

    /**
     * @test
     */
    public function testGetLogin()
    {
        // Mock responses.
        $handler = HandlerStack::create(new MockHandler([
            $expectedResponse = new Response(200)
        ]));

        $httpClient = new HttpClient(compact('handler'));

        $instance = new Client($httpClient);

        $response = $instance->getLogin();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testPostLogin()
    {
        // Mock responses.
        $handler = HandlerStack::create(new MockHandler([
            new Response(200)
        ]));

        // Record requests.
        $container = [];
        $history = Middleware::history($container);
        $handler->push($history);

        $httpClient = new HttpClient(compact('handler'));

        $instance = new Client($httpClient);

        $response = $instance->postLogin($email = 'EMAIL', $password = 'PASSWORD');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $container);

        $body = $container[0]['request']->getBody()->__toString();

        $this->assertContains($email, $body);
        $this->assertContains($password, $body);
    }

    /**
     * @test
     */
    public function testGetContinue()
    {
        // Mock responses.
        $handler = HandlerStack::create(new MockHandler([
            new Response(200)
        ]));

        // Record requests.
        $container = [];
        $history = Middleware::history($container);
        $handler->push($history);

        $httpClient = new HttpClient(compact('handler'));

        $instance = new Client($httpClient);

        $response = $instance->continue($url = 'https://example.com');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $container);

        $this->assertEquals($url, $container[0]['request']->getUri()->__toString());
    }

    /**
     * @test
     */
    public function testGetMoney()
    {
        // Mock responses.
        $handler = HandlerStack::create(new MockHandler([
            new Response(200)
        ]));

        // Record requests.
        $container = [];
        $history = Middleware::history($container);
        $handler->push($history);

        $httpClient = new HttpClient(compact('handler'));

        $instance = new Client($httpClient);

        $response = $instance->getMoney([
            $key = 'month' => $value = '201906',
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertCount(1, $container);

        $query = $container[0]['request']->getUri()->getQuery();

        $this->assertContains($key, $query);
        $this->assertContains($value, $query);
    }
}
