<?php

namespace App\Zaim\Parsers;

use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    /**
     * Page content.
     *
     * @var string
     */
    protected $html;

    /**
     * DOM Crawler.
     *
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     * コンストラクタ
     *
     * @param  string  $html
     * @return void
     */
    public function __construct(string $html)
    {
        $this->html = $html;
        $this->crawler = new Crawler($this->html);
    }

    /**
     * Create a instance from HTTP response.
     *
     * @param  \GuzzleHttp\Psr7\Response  $response
     * @return self
     */
    public static function from(Response $response): self
    {
        $html = (string) $response->getBody();

        return new static($html);
    }
}
