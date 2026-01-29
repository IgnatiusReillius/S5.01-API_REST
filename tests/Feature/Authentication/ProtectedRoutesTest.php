<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProtectedRoutesTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_access_protected_route_with_token() : void  {
        
        $user = User::factory()->create();
        $token = $user->createToken('test')->accessToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
                'email' => $user->email,
            ]);
    }

    public function test_user_cannot_access_protected_route_without_token() : void {

        $this->getJson('/api/me')
            ->assertStatus(401);
    }

    public function test_user_cannot_access_protected_route_with_invalid_token() : void {

        $this->withHeader('Authorization', "Bearer 123fake")
            ->getJson('/api/me')
            ->assertStatus(401);
    }
}
