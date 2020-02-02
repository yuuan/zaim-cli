<?php

namespace App\Zaim\Parsers;

use InvalidArgumentException;
use App\Entities\Payment;
use App\Exceptions\ParseException;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class MoneyParser extends Parser
{
    /**
     * The year this page is targeted.
     *
     * @var int
     */
    private $year;

    /**
     * Get whether it is the expected page.
     *
     * @return bool
     */
    public function isExpected(): bool
    {
        return mb_strpos($this->html, '日ごとの履歴') !== false;
    }

    /**
     * Get payments from HTML.
     *
     * @return array
     */
    public function getPayments(): array
    {
        $payments = [];

        $rows = $this->crawler->filter('table.list > tbody.money-list > tr');

        $rows->each(function (Crawler $row, int $i) use (&$payments) {
            $payment = new Payment;

            $payment->date = $this->parseDate(
                $row->children('td.date a')->text()
            );

            $payment->category = $this->flat(
                $row->children('td.category a')->text()
            );

            $payment->price = $this->parsePrice(
                $row->children('td.price a')->text()
            );

            try {
                $payment->income = $row->children('td.from_account a img')->attr('alt');
            } catch (InvalidArgumentException $e) { }

            try {
                $payment->spend = $row->children('td.to_account a img')->attr('alt');
            } catch (InvalidArgumentException $e) { }

            $payment->place = $this->flat(
                $row->children('td.place a')->text()
            );

            $payment->name = $this->flat(
                $row->children('td.name div.name')->text()
            );

            $payment->comment = $this->flat(
                $row->children('td.comment div.comment')->text()
            );

            $payments[] = $payment;
        });

        return $payments;
    }

    /**
     * Get the year this page is targeted.
     *
     * @return int
     *
     * @throws \App\Exceptions\ParseException
     */
    public function getYear(): int
    {
        if (is_null($this->year)) {
            try {
                $text = $this->crawler->filter('h2.btn-jump-to-month')->text();
            } catch (InvalidArgumentException $e) {
                throw new ParseException('Target year field is not found in page.');
            }

            if (preg_match('/([1-9][0-9]*) 年/', $text, $matches) !== 1) {
                throw new ParseException('Target year is not found in page.');
            }

            $this->year = (int) $matches[1];
        }

        return $this->year;
    }

    /**
     * Parse the date string.
     *
     * @param  string  $date
     * @return \Carbon\Carbon
     *
     * @throws \App\Exceptions\ParseException
     */
    private function parseDate(string $date): Carbon
    {
        $year = $this->getYear();

        if (preg_match('/([1-9]|1[0-2])月([0-9]|[1-2][0-9]|3[0-1])日/', $date, $matches) !== 1) {
            throw new ParseException('Could not parse the date.');
        }

        $month = (int) $matches[1];
        $day = (int) $matches[2];

        return Carbon::create($year, $month, $day, 0, 0, 0);
    }

    /**
     * Parse the price string.
     *
     * @param  string  $price
     * @return int
     *
     * @throws \App\Exceptions\ParseException
     */
    private function parsePrice(string $price): int
    {
        if (preg_match('/¥([0-9,\-]+)/', $price, $matches) !== 1) {
            throw new ParseException('Could not parse the price.');
        }

        return (int) str_replace(',', '', $matches[1]);
    }

    /**
     * Remove the line feed code.
     *
     * @param  string  $string
     * @return string
     */
    private function flat(string $string)
    {
        return str_replace(["\r", "\n"], '', $string);
    }
}
