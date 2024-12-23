<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * A basic feature test example.
     */
    public function test_register_usuario_api(): void
    {
        $user = User::factory()->make();
        
        $response = $this->post('/api/v1/auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'teste123',
            'password_confirmation' => 'teste123'
        ]);
        
        $response->assertStatus(201);
    }

    public function test_login_api(): void
    {
        // Sanctum::actingAs(User::factory()->create())
        $user = User::factory()->make();
        $this->post('/api/v1/auth/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'teste123',
            'password_confirmation' => 'teste123'
        ]);

        $response = $this->post('/api/v1/auth/token', [
            'email' => $user->email,
            'password' => 'teste123'
        ]);
        $responsaData = $response->json();
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'usuario_logado',
            'tokenable_id' => $responsaData['user']['id'],
        ]);
    }
}
