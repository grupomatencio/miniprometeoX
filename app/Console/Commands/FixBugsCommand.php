<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixBugsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miniprometeo:fix-bugs-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corregir errores de los tickets y mandarls a la auxiliar que toca cada ticket en ve de descontarlo de la auxiliar 0 y del total de la máquina de cambio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('entrando al command de fix-bugs-command');
    }
}
