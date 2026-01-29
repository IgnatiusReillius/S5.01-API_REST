<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase 
{
    use RefreshDatabase;

    public function test_user_can_register_and_recieve_token() : void {
        
        $data = [
            'name' => 'Nacho',
            'email' => 'nacho@test.com',
            'password' => 'miContraseña!1',
            'password_confirmation' => 'miContraseña!1',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Nacho',
                'email' => 'nacho@test.com',
            ])
            ->assertJsonMissing(['password'])
            ->assertJsonStructure(['data', 'access_token']);

        $this->assertDatabaseHas('users', [
            'email' => 'nacho@test.com',
        ]);
    }

}