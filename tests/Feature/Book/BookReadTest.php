<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_books() : void {

        $user = User::factory()->create();
        
        Book::factory()->count(3)->create();

        Passport::actingAs($user);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_list_books() : void {

        $admin = User::factory()->admin()->create();
        
        Book::factory()->count(2)->create();

        Passport::actingAs($admin);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_unauthenticated_user_cannot_list_books() : void {

        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(401);
    }
}