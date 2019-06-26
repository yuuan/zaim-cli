<?php

namespace App;

use RuntimeException;
use App\Zaim\Client;
use App\Zaim\Parsers\MoneyParser;
use App\Zaim\Parsers\UsersAuthParser;
use Carbon\Carbon;

class Zaim
{
    /**
     * Zaim client instance.
     *
     * @var \App\Zaim\Client
     */
    private $client;

    /**
     * コンストラクタ
     *
     * @param  \App\Zaim\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Log in to Zaim.
     *
     * @param  string  $email
     * @param  string  $password
     * @return $this
     */
    public function login(string $email, string $password): self
    {
        $this->client->getLogin();

        $response = $this->client->postLogin($email, $password);

        $next = UsersAuthParser::from($response)->getNextUrl();

        usleep(500);

        $response = $this->client->continue($next);

        return $this;
    }

    /**
     * Get payments in Zaim.
     *
     * @param  \Carbon\Carbon  $month
     * @return array
     */
    public function getPayments(Carbon $month = null): array
    {
        if (is_null($month)) {
            $month = Carbon::today();
        }

        $response = $this->client->getMoney([
            'month' => $month->format('Ym'),
        ]);

        $payments = MoneyParser::from($response)->getPayments();

        return $payments;
    }
}
