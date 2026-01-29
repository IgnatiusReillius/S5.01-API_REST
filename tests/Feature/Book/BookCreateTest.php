<?php

namespace Tests\Feature\Book;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_book() : void {

        $admin = User::factory()->admin()->create();
        
        Passport::actingAs($admin);

        $response = $this->postJson('/api/books', [
            'title' => 'Sostener el cielo',
            'author' => 'Cixin Liu',
            'isbn' => '9788418037252',
            'publisher' => 'Nova',
            'publish_date' => '2021-09-09',
            'pages' => 392,
            'summary' => 'Sinopsis de este libro.',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'author', 'isbn', 'publisher', 'publish_date', 'pages', 'summary'
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'isbn' => '9788418037252',
        ]);
    }

    public function test_user_cannot_create_book() : void {
        
        $user = User::factory()->create();
        
        Passport::actingAs($user);

        $response = $this->postJson('/api/books', [
            'title' => 'Sostener el cielo',
            'author' => 'Cixin Liu',
            'isbn' => '9788418037252',
            'publisher' => 'Nova',
            'publish_date' => '2021-09-09',
            'pages' => 392,
            'summary' => 'Sinopsis de este libro.',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_create_book_with_invalid_data() : void {

        $admin = User::factory()->admin()->create();
        
        Passport::actingAs($admin);

        $response = $this->postJson('/api/books', [
            'title' => '',
            'author' => '',
            'isbn' => '',
            'publisher' => '',
            'publish_date' => '',
            'pages' => -10,
            'summary' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'title', 'author', 'isbn', 'publisher', 'publish_date', 'pages', 'summary'
            ]);
    }
}