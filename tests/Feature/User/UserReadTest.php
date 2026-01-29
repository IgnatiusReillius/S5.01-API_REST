<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users() : void {

        $users = User::factory()->count(2)->create();
        foreach($users as $user){
            Passport::actingAs($user);
        }
        
        $response = $this->getJson('/api/users');
        
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data'); 
    }

    public function test_can_show_user_by_id() : void {
        
        $user = User::factory()->create();
        Passport::actingAs($user);
        
        $response = $this->getJson("/api/users/{$user->id}");
        
        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $user->id,
                     'email' => $user->email                      
                 ]);
    }
}
