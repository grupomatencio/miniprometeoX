<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuxMoneyStorage;
use App\Models\AuxMoneyStorageInfo;
use App\Models\Machine;
use App\Providers\ApiClient;

class SendModerateDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'miniprometeo:send-moderate-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía datos de tablas que necesitan actualización moderada. Command para seleccionar las tablas que moderadamente guardara datos en prometeo.';

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

        $this->sendAuxMoneyStorageData($apiClient);
        $this->sendAuxMoneyStorageInfoData($apiClient);
        $this->sendMachinesData($apiClient);
    }

    private function sendAuxMoneyStorageData(ApiClient $apiClient)
    {
        $data = AuxMoneyStorage::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de AuxMoneyStorage enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de AuxMoneyStorage.');
        }
    }

    private function sendAuxMoneyStorageInfoData(ApiClient $apiClient)
    {
        $data = AuxMoneyStorageInfo::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de AuxMoneyStorageInfo enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de AuxMoneyStorageInfo.');
        }
    }

    private function sendMachinesData(ApiClient $apiClient)
    {
        $data = Machine::all()->toArray(); // Convertir a array para enviar
        $response = $apiClient->sendData('save-data', $data);

        if ($response) {
            $this->info('Datos de Machine enviados con éxito.');
        } else {
            $this->error('Error al enviar los datos de Machine.');
        }
    }
}
