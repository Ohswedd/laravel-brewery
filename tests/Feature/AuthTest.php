<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

/**
 * Test per verificare il processo di autenticazione.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test per verificare il login con credenziali corrette.
     *
     * @return void
     */
    public function test_user_can_login_with_correct_credentials()
    {
        // Creazione dell'utente
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
        ]);

        // Tentativo di login
        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        // Verifica della risposta
        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type']);
    }
}
