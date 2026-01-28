<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users() : void {

        User::factory()->count(2)->create();
        
        $response = $this->getJson('/api/users');
        
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data'); 
    }

    public function test_can_show_user_by_id() : void {
        
        $user = User::factory()->create();
        
        $response = $this->getJson("/api/users/{$user->id}");
        
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $user->id,
                     'email' => $user->email                      
                 ]);
    }
}
