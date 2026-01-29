<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_user() : void {

        $user = User::factory()->create([
            'password' => bcrypt('miContraseña!1')
        ]);
        Passport::actingAs($user);
        
        $updatedData = [
            'email' => 'jose@email.com',
            'password' => 'miNuevaContraseña!2',
            'password_confirmation' => 'miNuevaContraseña!2'
        ];
        
        $response = $this->putJson("/api/users/{$user->id}", $updatedData);
        
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'email' => 'jose@email.com',
                 ]);
        
        $user->refresh();
        
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'email' => $updatedData['email'],
        ]);
        
        $this->assertTrue(Hash::check('miNuevaContraseña!2', $user->password));
        $this->assertFalse(Hash::check('miContraseña!1', $user->password));
    }
    
    public function test_cannot_update_user_with_invalid_data() : void {
        
        $user = User::factory()->create();
        Passport::actingAs($user);
        
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different'
        ];
        
        $response = $this->putJson("/api/users/{$user->id}", $data);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }
}
