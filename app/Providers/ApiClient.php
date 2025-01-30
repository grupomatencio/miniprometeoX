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
            return $this->postRequest($endpoint, $data, $token);
        }

        logger()->error('No se pudo obtener el token de acceso antes de enviar los datos.');
        return null;
    }

    private function postRequest(string $endpoint, array $data, string $token)
    {
        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/{$endpoint}", $data);

            if ($response->successful()) {
                logger()->info('Datos enviados con éxito.', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                return $response;
            }

            logger()->error('Error al enviar datos.', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            logger()->error('Excepción al enviar datos.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    public function getAccessToken(User $user, string $password)
    {
        // Buscar un token activo en la base de datos
        $activeToken = $user->tokens()
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->latest('created_at')
            ->first();

        if ($activeToken) {
            Log::info('Usando token de acceso existente.');
            return $activeToken->id; // Se usa el ID porque el token en sí está hasheado
        }

        // Si no hay un token activo, intenta refrescarlo
        if ($user->refresh_token) {
            return $this->refreshAccessToken($user->refresh_token);
        }

        // Si no hay refresh_token o falla la renovación, solicita un nuevo token
        return $this->requestNewAccessToken($user, $password);
    }

    private function requestNewAccessToken(User $user, string $password)
    {
        try {
            Log::info('Solicitando nuevo token de acceso para el usuario.', [
                'email' => $user->email,
            ]);

            $response = Http::asForm()->post(config('app.api_server_url') . '/oauth/token', [
                'grant_type' => 'password',
                'client_id' => config('app.passport_client_id'),
                'client_secret' => config('app.passport_client_secret'),
                'username' => $user->email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Token de acceso obtenido correctamente.', $data);

                // Almacenar en la sesión
                session([
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]);

                $this->storeToken($user, $data);
                return $data['access_token'];
            }

            Log::error('Error al obtener el token de acceso.', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al intentar obtener el token de acceso.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    //para pruebas tinker
    public function refreshTokenForUser(User $user) {
        if ($user->refresh_token) {
            return $this->refreshAccessToken($user->refresh_token);
        }
        return null;
    }

    private function refreshAccessToken(string $refreshToken)
    {
        try {
            Log::info('Intentando refrescar el token de acceso.');

            $response = Http::asForm()->post(config('app.api_server_url') . '/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => config('app.passport_client_id'),
                'client_secret' => config('app.passport_client_secret'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Token refrescado correctamente.', $data);

                return $data['access_token'];
            }

            Log::error('Error al refrescar el token.', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Excepción al intentar refrescar el token.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    private function storeToken(User $user, array $data)
    {
        $expiresAt = now()->addSeconds($data['expires_in']);

        // Revoca los tokens antiguos
        $user->tokens()->where('revoked', false)->update(['revoked' => true]);

        // Guarda el refresh_token en el usuario
        $user->update([
            'refresh_token' => $data['refresh_token'],
        ]);

        Log::info('Token almacenado en la base de datos.', [
            'expires_at' => $expiresAt,
        ]);
    }
}
