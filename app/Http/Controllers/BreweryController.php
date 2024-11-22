<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Controller per la gestione delle birrerie tramite OpenBreweryDB.
 */
class BreweryController extends Controller
{
    /**
     * Recupera una lista paginata di birrerie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validazione dei parametri di paginazione
        $validated = $request->validate([
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:50',
        ]);

        $page = $validated['page'] ?? 1;
        $perPage = $validated['per_page'] ?? 20;

        // Chiamata all'API di OpenBreweryDB
        $response = Http::get('https://api.openbrewerydb.org/breweries', [
            'page' => $page,
            'per_page' => $perPage,
        ]);

        // Verifica della risposta
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['message' => 'Errore nel recupero delle birrerie'], $response->status());
        }
    }
}
