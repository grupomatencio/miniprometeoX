<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\MoneySynchronizationEveryTimeJob;
use App\Jobs\MoneySynchronizationJob;
use App\Jobs\MoneySynchronization24hJob;
use App\Jobs\MoneySynchronizationAuxMoneyStorageJob;
use App\Jobs\MoneySynchronizationConfigJob;
use App\Jobs\SendCasualDataJob;
use App\Jobs\SendFrequentDataJob;
use App\Jobs\SendModerateDataJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// TRABAJOS QUE SE DEBEN HACER PARA SINCRONIZAR "MONEY CON MINIPROMETEO"

// se debe ejecutar al empezar con miniprometeo
Schedule::job(new MoneySynchronizationJob) -> everyThirtySeconds();

// se debe ejecutar al principio y cada vez que se cambien las auxiliares
Schedule::job(new MoneySynchronizationAuxmoneystorageJob) -> everyThirtySeconds();

// se debe ejecutar cada 30 seg
Schedule::job(new MoneySynchronizationEveryTimeJob) -> everyThirtySeconds();

// cada 24h o cuando hagan falta
Schedule::job(new MoneySynchronization24hJob) -> everyThirtySeconds();

// cada vez que se hagan cambios en la configuracion de la money
Schedule::job(new MoneySynchronizationConfigJob) -> everyThirtySeconds();


// TRABAJOS QUE SE DEBEN HACER PARA SINCRONIZAR "MINIPROMETEO CON PROMETEO" ENVIO DE DATOS

// se debe ejecutar cada 30 seg
//Schedule::job(new SendFrequentDataJob) -> everyThirtySeconds();

// cada 24h o cuando hagan falta
//Schedule::job(new SendModerateDataJob) -> everyThirtySeconds();

// cada vez que se hagan cambios en la configuracion de la money
//Schedule::job(new SendCasualDataJob) -> everyThirtySeconds();


// faltaria configurarlo con los tiempos preciosos para cada job
