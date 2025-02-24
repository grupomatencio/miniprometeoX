<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function destroy($id)
    {
        dd('Método destroy ejecutado en ClientController con ID: ' . $id);
    }
}
