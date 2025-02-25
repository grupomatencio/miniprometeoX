<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function destroy($id)
    {
        // Buscar el cliente por ID
        $cliente = Client::find($id);

        // Verificar si el cliente existe
        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }

        // Eliminar el cliente
        $cliente->delete();

        // Volver a la página anterior con un mensaje de éxito
        return redirect()->back()->with('success', 'Cliente eliminado correctamente.');
    }
}
