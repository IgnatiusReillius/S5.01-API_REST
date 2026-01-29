<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
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

    public function test_cannot_login_without_email() : void {

        $response = $this->postJson('/api/login', [
            'password' => 'miContraseña!1',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.']
                ]
        ]);
    }
    
    public function test_cannot_login_without_password() : void {

        $response = $this->postJson('/api/login', [
            'email' => 'nacho@prueba.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password'])
            ->assertJson([
                'errors' => [
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    public function test_cannot_login_with_invalid_email() : void {

        $response = $this->postJson('/api/login', [
            'email' => 'emailNacho',
            'password' => 'miContraseña!1',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJson([
                'errors' => [
                    'email' => ['The email field must be a valid email address.']
                ]
            ]);
    }

    public function test_cannot_login_with_non_existent_email() : void {

        $response = $this->postJson('/api/login', [
            'email' => 'nadie@email.com',
            'password' => 'miContraseña!1',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_cannot_login_with_wrong_password() : void {

        User::factory()->create([
            'email' => 'nacho@prueba.com',
            'password' => Hash::make('miContraseña!1'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'nacho@prueba.com',
            'password' => 'otraContraseña!2',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_cannot_login_without_both_email_and_password() : void {

        $response = $this->postJson('/api/login', [
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_user_can_logout_and_token_is_revoked_after_logout() : void {
        $user = User::factory()->create();
        $tokenResponse = $user->createToken('test');
        $token = $tokenResponse->accessToken;
        $tokenId = $tokenResponse->token->id;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/logout')
            ->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id' => $tokenId,
            'revoked' => true,
        ]);
    }
}
