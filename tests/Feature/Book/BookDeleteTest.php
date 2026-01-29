<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_specific_book() : void {
        
        $admin = User::factory()->admin()->create();
        
        $book = Book::factory()->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_non_admin_cannot_delete_book() : void {

        $user = User::factory()->create();
        
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_delete_book() : void {

        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(401);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);
    }

    public function test_admin_can_delete_all_books() : void {

        $admin = User::factory()->admin()->create();
        
        Book::factory()->count(5)->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson('/api/books');

        $response->assertStatus(204);

        $this->assertEquals(0, Book::count());
    }

}