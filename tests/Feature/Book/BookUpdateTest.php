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
            'isbn' => $book->isbn,
            'publisher' => $book->publisher,
            'publish_date' => $book->publish_date->format('Y-m-d'),
            'pages' => $book->pages,
            'summary' => $book->summary,
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
}