<?php

namespace App\Zaim;

use RuntimeException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\Response;

class Client
{
    /**
     * HTTP client
     *
     * @var \GuzzleHttp\Client
     */
    public $client;

    /**
     * Cookie jar instance.
     *
     * @var \GuzzleHttp\Cookie\CookieJar
     */
    public $cookies;

    /**
     * Create a Zaim client instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return void
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
        $this->cookies = new CookieJar;
    }

    /**
     * Access login page.
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getLogin(): Response
    {
        return $this->client->request('GET', 'https://auth.zaim.net/', [
            'cookies' => $this->cookies,
        ]);
    }

    /**
     * Send login request.
     *
     * @param  string  $email
     * @param  string  $password
     * @return \GuzzleHttp\Psr7\Response
     */
    public function postLogin(string $email, string $password): Response
    {
        return $this->post('https://auth.zaim.net/', [
            '_method' => 'POST',
            'data[User][email]' => $email,
            'data[User][password]' => $password,
            'oauth_token' => 'dummy',
            'agree' => '1',
        ]);
    }

    /**
     * Continue to login.
     *
     * @param  string  $nextUrl
     * @return \GuzzleHttp\Psr7\Response
     */
    public function continue(string $nextUrl): Response
    {
        return $this->get($nextUrl);
    }

    /**
     * Get a list of payments.
     *
     * @param  array  $params
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getMoney(array $params): Response
    {
        return $this->get('https://zaim.net/money', $params);
    }

    /**
     * Send GET request.
     *
     * @param  string  $url
     * @param  ?array  $params
     * @return \GuzzleHttp\Psr7\Response
     */
    private function get(string $url, array $params = null): Response
    {
        return $this->client->request('GET', $url, [
            'cookies' => $this->cookies,
            'query' => $params,
        ]);
    }

    /**
     * Send POST request.
     *
     * @param  string  $url
     * @param  ?array  $params
     * @return \GuzzleHttp\Psr7\Response
     */
    private function post(string $url, array $params = null): Response
    {
        return $this->client->request('POST', $url, [
            'cookies' => $this->cookies,
            'form_params' => $params,
        ]);
    }
}
