<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class CurrencyUpdateScheduler extends ConsoleKernel
{
    /**
     * {@inheritdoc}
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('currency:fetch')->twiceDaily(0, 12);
    }
}
