<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\MoneySynchronizationEveryTimeJob;
use App\Jobs\MoneySynchronizationJob;
use App\Jobs\MoneySynchronization24hJob;
use App\Jobs\MoneySynchronizationAuxMoneyStorageJob;
use App\Jobs\MoneySynchronizationConfigJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new MoneySynchronizationEveryTimeJob) -> everyThirtySeconds();
Schedule::job(new MoneySynchronizationJob) -> everyThirtySeconds();
Schedule::job(new MoneySynchronization24hJob) -> everyThirtySeconds();
Schedule::job(new MoneySynchronizationAuxmoneystorageJob) -> everyThirtySeconds();
Schedule::job(new MoneySynchronizationConfigJob) -> everyThirtySeconds();
