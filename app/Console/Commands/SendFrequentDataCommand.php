<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Acumulado;
use App\Models\CollectDetail;
use App\Models\Collect;
use App\Models\Log;
use App\Models\Ticket;
use App\Providers\ApiClient; // Asegúrate de incluir el ApiClient

class SendFrequentDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miniprometeo:send-frequent-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía datos de tablas que necesitan actualización frecuente. Command para seleccionar las tablas que frecuentemente guardara datos en prometeo.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Instanciar el ApiClient
        $apiClient = new ApiClient(
            config('app.api_server_url'), // Asegúrate de tener configurado esto en tu archivo .env
            env('PASSPORT_CLIENT_ID'),
            env('PASSPORT_CLIENT_SECRET')
        );

        $this->sendAcumuladoData($apiClient);
        $this->sendCollectDetailsData($apiClient);
        $this->sendCollectsData($apiClient);
        $this->sendLogsData($apiClient);
        $this->sendTicketsData($apiClient);
    }

    private function sendAcumuladoData(ApiClient $apiClient)
    {
        $data = Acumulado::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de Acumulado enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Acumulado.');
        }
    }

    private function sendCollectDetailsData(ApiClient $apiClient)
    {
        $data = CollectDetail::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de CollectDetail enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de CollectDetail.');
        }
    }

    private function sendCollectsData(ApiClient $apiClient)
    {
        $data = Collect::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de Collect enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Collect.');
        }
    }

    private function sendLogsData(ApiClient $apiClient)
    {
        $data = Log::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de Log enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Log.');
        }
    }

    private function sendTicketsData(ApiClient $apiClient)
    {
        $data = Ticket::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de Ticket enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Ticket.');
        }
    }
}
