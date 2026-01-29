<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_refresh_token() : void {
        
        User::factory()->create([
            'email' => 'nacho@prueba.com',
            'password' => bcrypt('miContraseña!1')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'nacho@prueba.com',
            'password' => 'miContraseña!1'
        ]);

        $originalToken = $loginResponse->json('access_token');

        $response = $this->withHeader('Authorization', "Bearer {$originalToken}")
            ->postJson('/api/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);

        $newToken = $response->json('access_token');

        $this->assertNotEquals($originalToken, $newToken);
        $this->assertNotEmpty($newToken);
    }

    public function test_refresh_token_revokes_old_token() : void {
        
        $user = User::factory()->create([
            'email' => 'nacho@prueba.com',
            'password' => bcrypt('miContraseña!1')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'nacho@prueba.com',
            'password' => 'miContraseña!1'
        ]);

        $originalToken = $response->json('access_token');

        $this->withHeader('Authorization', "Bearer {$originalToken}")
            ->postJson('/api/refresh')
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'user_id' => $user->id,
            'revoked' => true,
        ]);
    }

    public function test_new_token_can_access_protected_routes() : void {

        $user = User::factory()->create([
            'email' => 'nacho@prueba.com',
            'password' => bcrypt('miContraseña!1')
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'nacho@prueba.com',
            'password' => 'miContraseña!1'
        ]);

        $originalToken = $loginResponse->json('access_token');

        $refreshResponse = $this->withHeader('Authorization', "Bearer {$originalToken}")
            ->postJson('/api/refresh');

        $newToken = $refreshResponse->json('access_token');

        $this->withHeader('Authorization', "Bearer {$newToken}")
            ->getJson('/api/me')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'email' => 'nacho@prueba.com'
                ]
        ]);
    }
}