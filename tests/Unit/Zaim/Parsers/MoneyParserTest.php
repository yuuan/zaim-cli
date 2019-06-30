<?php

namespace Tests\Unit\Zaim\Parsers;

use App\Entities\Payment;
use App\Zaim\Parsers\MoneyParser;
use Tests\TestCase;

class MoneyParserTest extends TestCase
{
    /**
     * @test
     */
    public function testIsExpectedWithExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/money.html')
        );

        $instance = new MoneyParser($html);

        $this->assertTrue($instance->isExpected());
    }

    /**
     * @test
     */
    public function testIsExpectedWithNotExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/login.html')
        );

        $instance = new MoneyParser($html);

        $this->assertFalse($instance->isExpected());
    }

    /**
     * @test
     */
    public function testGetPaymentsWithExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/money.html')
        );

        $instance = new MoneyParser($html);

        $payments = $instance->getPayments();

        $this->assertIsArray($payments);
        $this->assertContainsOnlyInstancesOf(Payment::class, $payments);
    }

    /**
     * @test
     */
    public function testGetPaymentsWithNotExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/login.html')
        );

        $instance = new MoneyParser($html);

        $payments = $instance->getPayments();

        $this->assertIsArray($payments);
        $this->assertCount(0, $payments);
    }

    /**
     * @test
     */
    public function testGetYearWithExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/money.html')
        );

        $instance = new MoneyParser($html);

        $this->assertEquals(2019, $instance->getYear());
    }

    /**
     * @test
     * @expectedException \App\Exceptions\ParseException
     * @expectedExceptionMessage Target year is not found in page.
     */
    public function testGetYearWithInvalidYear()
    {
        $html = file_get_contents(
            base_path('tests/stubs/money_with-invalid-year.html')
        );

        $instance = new MoneyParser($html);

        $instance->getYear();
    }

    /**
     * @test
     * @expectedException \App\Exceptions\ParseException
     * @expectedExceptionMessage Target year field is not found in page.
     */
    public function testGetYearWithNotExpectedHtml()
    {
        $html = file_get_contents(
            base_path('tests/stubs/login.html')
        );

        $instance = new MoneyParser($html);

        $instance->getYear();
    }
}
