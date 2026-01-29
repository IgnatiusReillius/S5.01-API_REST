<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_user() : void {

        $password = 'miContraseña!1';

        $user = User::factory()->create([
            'email' => 'nacho@prueba.com',
            'password' => Hash::make($password),
        ]);
        
        $response = $this->postJson('/api/login', [
            'email' => $user['email'],
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token'
        ]);
        
        $token = $response->json('access_token');
        
        $this->assertNotEmpty($token);
    }
}
