<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendDataToServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // Define la URL utilizando las constantes IP y PUERTO
        $url = 'http://' . PROMETEO_PRINCIPAL_IP . ':' . PROMETEO_PRINCIPAL_PORT . '/api/save-data';

        // Envía los datos a la ruta especificada
        $response = Http::post($url, $this->data);

        // Maneja la respuesta, si es necesario
        if ($response->successful()) {
            // Lógica en caso de éxito
        } else {
            // Lógica en caso de error
        }
    }
}
