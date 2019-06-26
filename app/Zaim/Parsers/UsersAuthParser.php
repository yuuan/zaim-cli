<?php

namespace App\Zaim\Parsers;

use App\Exceptions\ParseException;
use Symfony\Component\DomCrawler\Crawler;

class UsersAuthParser extends Parser
{
    /**
     * Get whether authentication succeeded.
     *
     * @return bool
     */
    public function isSucceeded(): bool
    {
        return mb_strpos($this->html, '認証が完了') !== false;
    }

    /**
     * Get next url.
     *
     * @return string
     *
     * @throws \App\Exceptions\ParseException
     */
    public function getNextUrl(): string
    {
        if (! $this->isSucceeded()) {
            throw new ParseException('Authentication is failed.');
        }

        $script = $this->crawler
                       ->filter('script')
                       ->reduce(function (Crawler $node, int $i) {
                           return mb_strpos($node->text(), 'location.href') !== false;
                       });

        if ($script->count() === 0) {
            throw new ParseException('Can not find script tag including redirect URL.');
        }

        if (preg_match('/"([!-~]+)"/', $script->text(null), $matches) !== 1) {
            throw new ParseException('Can not find the redirect URL.');
        }

        return $matches[1];
    }
}
