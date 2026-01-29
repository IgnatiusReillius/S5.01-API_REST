<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_book() : void {
        
        $admin = User::factory()->admin()->create();
        
        $book = Book::factory()->create([
            'title' => 'El Problema de los Tres Cuerpos',
            'author' => 'Cixin Liu',
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => 'El fin de la eternidad',
            'author' => 'Isaac Asimov',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'El fin de la eternidad',
                'author' => 'Isaac Asimov',
            ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'El fin de la eternidad',
            'author' => 'Isaac Asimov',
        ]);
    }

    public function test_non_admin_cannot_update_book() : void {
        
        $user = User::factory()->create();
        
        $book = Book::factory()->create([
            'title' => 'El Problema de los Tres Cuerpos',
        ]);

        Passport::actingAs($user);

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => 'El fin de la eternidad',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'El Problema de los Tres Cuerpos',
        ]);
    }

    public function test_unauthenticated_user_cannot_update_book() : void {
        
        $book = Book::factory()->create();

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => 'El fin de la eternidad',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_cannot_update_book_with_invalid_data() : void {
        
        $admin = User::factory()->admin()->create();
        
        $book = Book::factory()->create();

        Passport::actingAs($admin);

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => '',
            'author' => '',
            'isbn' => '',
            'publisher' => '',
            'publish_date' => 'fecha-invalida',
            'pages' => -10,
            'summary' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'title', 'author', 'isbn', 'publisher', 'publish_date', 'pages', 'summary'
            ]);
    }

    public function test_admin_cannot_update_book_with_duplicate_isbn() : void {
        
        $admin = User::factory()->admin()->create();
        
        Book::factory()->create(['isbn' => '1234567890123']);
        $book2 = Book::factory()->create(['isbn' => '9876543210987']);

        Passport::actingAs($admin);

        $response = $this->putJson("/api/books/{$book2->id}", [
            'title' => $book2->title,
            'author' => $book2->author,
            'isbn' => '1234567890123',
            'publisher' => $book2->publisher,
            'publish_date' => $book2->publish_date->format('Y-m-d'),
            'pages' => $book2->pages,
            'summary' => $book2->summary,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['isbn']);
    }

    public function test_returns_404_when_updating_non_existent_book() : void {
        
        $admin = User::factory()->admin()->create();
        
        Passport::actingAs($admin);

        $response = $this->putJson('/api/books/99999', [
            'title' => 'Test',
            'author' => 'Test',
            'isbn' => '1234567890123',
            'publisher' => 'Test',
            'publish_date' => '2024-01-01',
            'pages' => 100,
            'summary' => 'Test',
        ]);

        $response->assertStatus(404);
    }
}