<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Models\CollectDetailsInfo;
use App\Models\CollectInfo;
use App\Models\HcMoneyStorage;
use App\Models\HcMoneyStorageInfo;
use App\Models\BetMoneyStorage;
use App\Models\BetMoneyStorageInfo;
use App\Models\Config;
use App\Models\Session;
use App\Models\SessionsTicketServer;
use App\Providers\ApiClient;

class SendCasualDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miniprometeo:send-casual-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía datos de tablas que necesitan actualización casual. Command para seleccionar las tablas que casualmente guardara datos en prometeo.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Instanciar el ApiClient
        $apiClient = app(ApiClient::class); // Usar el contenedor de servicios para obtener la instancia

        // Intentar obtener el token de acceso
        $token = $apiClient->getAccessToken();
        Log::info($token);
        if (!$token) {
            $this->error('No se pudo obtener el token de acceso. Verifica las credenciales.' . $token);
            return;
        }

        $this->sendCollectDetailsInfoData($apiClient);
        $this->sendCollectInfoData($apiClient);
        $this->sendHcMoneyStorageData($apiClient);
        $this->sendHcMoneyStorageInfoData($apiClient);
        $this->sendBetMoneyStorageData($apiClient);
        $this->sendBetMoneyStorageInfoData($apiClient);
        $this->sendConfigData($apiClient);
        $this->sendSessionsData($apiClient);
    }

    private function sendCollectDetailsInfoData(ApiClient $apiClient)
    {
        $data = CollectDetailsInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de CollectDetailsInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de CollectDetailsInfo.');
        }
    }

    private function sendCollectInfoData(ApiClient $apiClient)
    {
        $data = CollectInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de CollectInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de CollectInfo.');
        }
    }

    private function sendHcMoneyStorageData(ApiClient $apiClient)
    {
        $data = HcMoneyStorage::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de HcMoneyStorage enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de HcMoneyStorage.');
        }
    }

    private function sendHcMoneyStorageInfoData(ApiClient $apiClient)
    {
        $data = HcMoneyStorageInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de HcMoneyStorageInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de HcMoneyStorageInfo.');
        }
    }

    private function sendBetMoneyStorageData(ApiClient $apiClient)
    {
        $data = BetMoneyStorage::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de BetMoneyStorage enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de BetMoneyStorage.');
        }
    }

    private function sendBetMoneyStorageInfoData(ApiClient $apiClient)
    {
        $data = BetMoneyStorageInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de BetMoneyStorageInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de BetMoneyStorageInfo.');
        }
    }

    private function sendConfigData(ApiClient $apiClient)
    {
        $data = Config::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de Config enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Config.');
        }
    }

    private function sendSessionsData(ApiClient $apiClient)
    {
        $data = SessionsTicketServer::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('api/save-data', $data);

        if ($response) {
            $this->info('Datos de Session Ticket Server enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Session.');
        }
    }
}
