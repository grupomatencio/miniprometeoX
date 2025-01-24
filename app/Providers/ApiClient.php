<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ApiClient
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct($baseUrl, $clientId, $clientSecret)
    {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        logger()->info('ApiClient inicializado.', [
            'baseUrl' => $this->baseUrl,
            'clientId' => $this->clientId,
        ]);
    }

    /**
     * Obtener un token de acceso desde el servidor o usar el existente.
     */
    public function getAccessToken(User $user, string $password)
    {
        // Si ya existe un token válido
        $existingToken = $user->tokens()->latest('created_at')->first();

        if ($existingToken && !$this->isTokenExpired($existingToken->expires_at)) {
            logger()->info('Usando token de acceso existente.');
            return $existingToken->token;
        }

        // Solicitar un nuevo token al servidor
        try {
            logger()->info('Solicitando nuevo token de acceso para el usuario.', [
                'email' => $user->email,
            ]);

            $response = Http::asForm()->post("{$this->baseUrl}/oauth/token", [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $user->email,
                'password' => $password, // Asegúrate de pasar la contraseña directamente
            ]);

            if ($response->successful()) {
                $data = $response->json();
                logger()->info('Token de acceso obtenido correctamente.');

                // Guardar el token en la base de datos
                $this->storeToken($user, $data['access_token'], $data['expires_in']);

                return $data['access_token'];
            } else {
                logger()->error('Error al obtener el token de acceso.', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Exception $e) {
            logger()->error('Excepción al intentar obtener el token de acceso.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    /**
     * Verifica si un token ha expirado.
     */
    private function isTokenExpired($expiresAt)
    {
        return $expiresAt ? now()->greaterThan($expiresAt) : true;
    }

    /**
     * Almacena el token y su fecha de expiración en la base de datos.
     */
    protected function storeToken(User $user, string $token, int $expiresIn)
    {
        // Calcula la fecha de expiración
        $expiresAt = now()->addSeconds($expiresIn);

        // Elimina tokens anteriores y guarda el nuevo
        $user->tokens()->delete();
        $user->createToken('Personal Access Token', ['*'])->accessToken;

        logger()->info('Token almacenado en la base de datos.', [
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Enviar datos al servidor a través de un endpoint.
     */
    public function sendData(User $user, string $password, string $endpoint, array $data)
    {
        logger()->info('Preparando envío de datos al servidor.', [
            'endpoint' => "{$this->baseUrl}/{$endpoint}",
            'data' => $data,
        ]);

        $token = $this->getAccessToken($user, $password);

        if ($token) {
            try {
                $response = Http::withToken($token)->post("{$this->baseUrl}/{$endpoint}", $data);

                if ($response->successful()) {
                    logger()->info('Datos enviados con éxito.', [
                        'status' => $response->status(),
                        'response_body' => $response->body(),
                    ]);
                    return $response;
                } else {
                    logger()->error('Error al enviar datos.', [
                        'status' => $response->status(),
                        'response_body' => $response->body(),
                    ]);
                    return null;
                }
            } catch (\Exception $e) {
                logger()->error('Excepción al enviar datos.', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } else {
            logger()->error('No se pudo obtener el token de acceso antes de enviar los datos.');
        }

        return null;
    }
}
