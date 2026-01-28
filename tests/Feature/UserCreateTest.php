<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user() : void {
        
        $response = $this->postJson('/api/users', [
            'name' => 'Nacho',
            'email' => 'nacho@prueba.com',
            'password' => 'miContraseña',
            'password_confirmation' => 'miContraseña',
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('users', [
            'name' => 'Nacho',
            'email' => 'nacho@prueba.com',
        ]);
    }

    public function test_cannot_create_user_with_invalid_data() : void {
        
        $response = $this->postJson('/api/users', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'one',
            'password_confirmation' => 'two',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
