<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

/**
 * Controller per la gestione delle pagine web.
 */
class WebController extends Controller
{
    /**
     * Mostra la home page con la lista delle birrerie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Verifica se l'utente è autenticato
        if (!Session::has('access_token')) {
            return redirect()->route('login');
        }

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);

        // Chiamata all'API interna per recuperare le birrerie
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . Session::get('access_token'),
        ])->get('http://127.0.0.1/api/breweries', [
            'page' => $page,
            'per_page' => $perPage,
        ]);

        $breweries = $response->json();

        return view('home', compact('breweries', 'page', 'perPage'));
    }

    /**
     * Mostra il form di login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Gestisce la richiesta di login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validazione dei dati di ingresso
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Chiamata all'API di login
        $response = Http::post('http://127.0.0.1/api/login', $credentials);

        if ($response->successful()) {
            // Salvataggio del token in sessione
            Session::put('access_token', $response->json()['access_token']);
            return redirect()->route('home');
        } else {
            return redirect()->back()->withErrors(['message' => 'Credenziali non valide']);
        }
    }

    /**
     * Gestisce il logout dell'utente.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Chiamata all'API di logout
        Http::withHeaders([
            'Authorization' => 'Bearer ' . Session::get('access_token'),
        ])->post('http://127.0.0.1/api/logout');

        // Rimozione del token dalla sessione
        Session::forget('access_token');

        return redirect()->route('login');
    }
}
