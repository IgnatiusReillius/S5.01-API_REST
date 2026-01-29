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
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'author', 'isbn', 'publisher', 'publish_date', 'pages', 'summary'
                ]
            ]);
    }
}