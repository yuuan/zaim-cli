<?php

namespace Tests\Feature;

use App\Entities\Payment;
use App\Zaim;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class GetMoneyCommandTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function testGetMoneyCommand()
    {
        // Create payments to be taken.
        $payments = array_map(function () {
            return $this->createPayment();
        }, range(0, 9));

        // Mock up the Zaim model.
        $zaim = Mockery::mock(Zaim::class);
        $zaim->shouldReceive('login')->andReturnSelf();
        $zaim->shouldReceive('getPayments')->andReturn($payments);
        $this->app->bind(Zaim::class, function () use ($zaim) {
            return $zaim;
        });

        $this->artisan('money:get')
             ->assertExitCode(0);
    }

    private function createPayment(): Payment
    {
        $payment = new Payment;

        $payment->date = Carbon::instance($this->faker->dateTimeThisMonth);
        $payment->category = $this->faker->word;
        $payment->price = $this->faker->randomNumber(6);
        $payment->income = $this->faker->word;
        $payment->spend = $this->faker->word;
        $payment->place = $this->faker->word;
        $payment->name = $this->faker->word;
        $payment->comment = $this->faker->sentence;

        return $payment;
    }
}
