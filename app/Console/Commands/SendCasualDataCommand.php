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
use App\Models\User; // Necesario para obtener el usuario
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
        $apiClient = app(ApiClient::class);
        Log::info('Instanciando ApiClient', ['apiClient' => json_encode($apiClient)]);

        $user = User::where('name', 'Miniprometeo')->first();
        $password = 'Mini1234';

        if (!$user) {
            $this->error('Usuario no encontrado.');
            return;
        }

        Log::info('Usuario encontrado', ['user' => $user]);
        Log::info('Enviando datos de CollectDetailsInfo');
        $this->sendCollectDetailsInfoData($apiClient, $user, $password);

        /*$this->sendCollectInfoData($apiClient, $user, $password);
        $this->sendHcMoneyStorageData($apiClient, $user, $password);
        $this->sendHcMoneyStorageInfoData($apiClient, $user, $password);
        $this->sendBetMoneyStorageData($apiClient, $user, $password);
        $this->sendBetMoneyStorageInfoData($apiClient, $user, $password);
        $this->sendConfigData($apiClient, $user, $password);
        $this->sendSessionsData($apiClient, $user, $password);*/
    }

    private function sendCollectDetailsInfoData(ApiClient $apiClient, User $user, string $password)
    {
        $data = CollectDetailsInfo::all()->toArray();
        Log::info('Datos a enviar', ['data' => $data]);

        try {
            $response = $apiClient->sendData($user, $password, 'api/save-data', $data);
            Log::info('Respuesta del servidor', ['response' => $response]);

            if ($response) {
                $this->info('Datos de CollectDetailsInfo enviados con éxito.');
            } else {
                $this->error('Error al enviar los datos de CollectDetailsInfo.');
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar datos', ['error' => $e->getMessage()]);
        }
    }

    private function sendCollectInfoData(ApiClient $apiClient, User $user, string $password)
    {
        $data = CollectInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de CollectInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de CollectInfo.');
        }
    }

    private function sendHcMoneyStorageData(ApiClient $apiClient, User $user, string $password)
    {
        $data = HcMoneyStorage::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de HcMoneyStorage enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de HcMoneyStorage.');
        }
    }

    private function sendHcMoneyStorageInfoData(ApiClient $apiClient, User $user, string $password)
    {
        $data = HcMoneyStorageInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de HcMoneyStorageInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de HcMoneyStorageInfo.');
        }
    }

    private function sendBetMoneyStorageData(ApiClient $apiClient, User $user, string $password)
    {
        $data = BetMoneyStorage::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de BetMoneyStorage enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de BetMoneyStorage.');
        }
    }

    private function sendBetMoneyStorageInfoData(ApiClient $apiClient, User $user, string $password)
    {
        $data = BetMoneyStorageInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de BetMoneyStorageInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de BetMoneyStorageInfo.');
        }
    }

    private function sendConfigData(ApiClient $apiClient, User $user, string $password)
    {
        $data = Config::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de Config enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Config.');
        }
    }

    private function sendSessionsData(ApiClient $apiClient, User $user, string $password)
    {
        $data = SessionsTicketServer::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData($user, $password, 'api/save-data', $data);

        if ($response) {
            $this->info('Datos de Session Ticket Server enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Session.');
        }
    }
}
