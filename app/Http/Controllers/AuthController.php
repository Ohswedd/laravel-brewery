<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller per la gestione dell'autenticazione degli utenti.
 */
class AuthController extends Controller
{
    /**
     * Effettua il login dell'utente e restituisce un token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validazione dei dati di ingresso
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentativo di autenticazione
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenziali non valide'], 401);
        }

        // Creazione del token
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Restituzione del token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Effettua il logout dell'utente revocando i token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // Revoca di tutti i token dell'utente autenticato
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Logout effettuato con successo']);
    }
}
