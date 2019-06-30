<?php

namespace Tests\Unit\Zaim\Parsers;

use App\Zaim\Parsers\UsersAuthParser;
use Tests\TestCase;

class UsersAuthParserTest extends TestCase
{
    /**
     * @test
     */
    public function testIsSucceededWithExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/logged-in.html')
        );

        $instance = new UsersAuthParser($html);

        $this->assertTrue($instance->isSucceeded());
    }

    /**
     * @test
     */
    public function testIsSucceededWithNotExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/login.html')
        );

        $instance = new UsersAuthParser($html);

        $this->assertFalse($instance->isSucceeded());
    }

    /**
     * @test
     */
    public function testGetNextUrlWithExpectedHtml()
    {
        $expectedUrl = 'https://zaim.net/user_session/callback?oauth_token=XHjcZjpbTdzb0Au1k4DFS50yV25eWvkah4NzLpyT9wpuTHD43PeEQYvl7Y1uyBfDeppa64&oauth_verifier=iM2Nvl03yD6TK1v7awSj57fN7TEUFh2vhQsqPiCEG2ybeEjHxyiRhDL6Ij5FvTrvQw';

        $html = file_get_contents(
            base_path('tests/stubs/logged-in.html')
        );

        $instance = new UsersAuthParser($html);

        $this->assertEquals($expectedUrl, $instance->getNextUrl());
    }

    /**
     * @test
     * @expectedException \App\Exceptions\ParseException
     * @expectedExceptionMessage Can not find script tag including redirect URL.
     */
    public function testGetNextUrlWithoutLocationHref()
    {
        $html = file_get_contents(
            base_path('tests/stubs/logged-in_without-location-href.html')
        );

        $instance = new UsersAuthParser($html);

        $instance->getNextUrl();
    }

    /**
     * @test
     * @expectedException \App\Exceptions\ParseException
     * @expectedExceptionMessage Can not find the redirect URL.
     */
    public function testGetNextUrlWithoutNextUrl()
    {
        $html = file_get_contents(
            base_path('tests/stubs/logged-in_without-next-url.html')
        );

        $instance = new UsersAuthParser($html);

        $instance->getNextUrl();
    }
}
