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

    public function test_register_fails_with_duplicate_email() : void {

        $this->postJson('/api/users', [
            'name' => 'Nacho',
            'email' => 'nacho@test.com',
            'password' => 'miContraseña!1',
            'password_confirmation' => 'miContraseña!1',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Otro',
            'email' => 'nacho@test.com',
            'password' => 'otraContraseña!2',
            'password_confirmation' => 'otraContraseña!2',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    
    public function test_register_fails_with_missing_fields() : void {

        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name','email','password']);
    }
}