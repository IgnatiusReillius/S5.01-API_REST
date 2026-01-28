<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_user_by_id() : void {
        
        $user = User::factory()->create();
        
        $response = $this->deleteJson("/api/users/{$user->id}");
        
        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
    
    public function test_can_delete_all_user() : void {
        
        $users = User::factory()->count(10)->create();
        
        $response = $this->deleteJson("/api/users");
        
        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('users', []);
    }
}
