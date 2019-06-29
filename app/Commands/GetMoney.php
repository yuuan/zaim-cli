<?php

namespace App\Commands;

use App\Entities\Payment;
use App\Zaim;
use App\Zaim\Parsers\UsersAuthParser;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class GetMoney extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'money:get {--month=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get money history from Zaim.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Zaim $zaim)
    {
        $email = config('zaim.auth.email');
        $password = config('zaim.auth.password');

        $zaim->login($email, $password);

        $payments = $zaim->getPayments(
            $this->getMonth(Carbon::today())
        );

        $table = $this->format($payments);

        $this->table(...$table);
    }

    /**
     * Get the month from option or default.
     *
     * @param  \Carbon\Carbon  $default
     * @return \Carbon\Carbon
     */
    private function getMonth(Carbon $default): Carbon
    {
        if (is_null($month = $this->option('month'))) {
            return $default;
        }

        return Carbon::createFromFormat('Ymd', $month.'01');
    }

    /**
     * Format payments for table method.
     *
     * @param  array  $payments
     * @return array
     */
    private function format(array $payments): array
    {
        $header = [
            '日付',
            'カテゴリ',
            '金額',
            '出金',
            '入金',
            'お店',
            '品目',
            'メモ',
        ];

        $body = [];

        foreach ($payments as $payment) {
            $body[] = [
                $payment->date->format('Y/m/d'),
                $payment->category,
                '¥' . number_format($payment->price),
                mb_strlen($payment->income) > 0 ? '*' : '',
                mb_strlen($payment->spend) > 0 ? '*' : '',
                $payment->place,
                $payment->name,
                $payment->comment,
            ];
        }

        return [$header, $body];
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
