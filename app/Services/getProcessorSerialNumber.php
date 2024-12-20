<?php
namespace app\Services;
use Illuminate\Support\Facades\Log;

use Faker\Core\Number;

class getProcessorSerialNumber {
    public function getSerialNumber() :string
    {
        // Para Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $serial = shell_exec('wmic cpu get ProcessorId');
            log::info('win');
            log::info($serial);
            return trim(preg_replace('/\s+/', ' ', $serial)); // cortar espacio
        }

        // Para Linux
        elseif (strtoupper(substr(PHP_OS, 0, 6)) === 'LINUX') {
            $output = "ID: C1 06 08 00 FF FB EB BF";  // solo para probar
            //shell_exec('sudo /usr/sbin/dmidecode -t 4 | grep ID');

            if ($output) {
                preg_match('/ID:\s*([a-fA-F0-9\s]+)/', $output, $matches);
                $serial = isset($matches[1]) ? trim($matches[1]) : null;
            } else {
                $serial = null;
            }+
            Log::info('lin');
            log::info($serial);
            return trim($serial);
        }

        return null; // Si hay otro
        }
}
