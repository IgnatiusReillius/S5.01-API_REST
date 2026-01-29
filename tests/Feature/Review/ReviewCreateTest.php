<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ReviewCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review_for_book() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'read_date' => '2024-01-15',
            'comment' => 'Excelente libro, muy recomendado!',
            'rating' => 5,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id_user',
                    'id_book',
                    'add_date',
                    'read_date',
                    'comment',
                    'rating',
                    'created_at',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('reviews', [
            'id_user' => $user->id,
            'id_book' => $book->id,
            'rating' => 5,
            'comment' => 'Excelente libro, muy recomendado!',
        ]);
    }

    public function test_user_cannot_create_review_for_another_user() : void {

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user1);

        $response = $this->postJson("/api/users/{$user2->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
            'comment' => 'Intento de hackeo',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_create_review_for_another_user() : void {

        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($admin);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_cannot_create_duplicate_review_for_same_book() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ])->assertStatus(201);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 4,
        ]);

        $response->assertStatus(409);
    }
    
    public function test_unauthenticated_user_cannot_create_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ]);

        $response->assertStatus(401);
    }
    
    public function test_rating_is_optional() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'comment' => 'Sin rating',
        ]);

        $response->assertStatus(201);
    }

    public function test_comment_is_optional() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ]);

        $response->assertStatus(201);
    }

    public function test_read_date_is_optional() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ]);

        $response->assertStatus(201);
    }
    
    public function test_rating_must_be_between_1_and_5() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 0,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['rating']);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 6,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['rating']);
    }
    
    public function test_add_date_defaults_to_today_if_not_provided() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now()->format('Y-m-d H:i:s'),
        ]);
    }
    
    public function test_returns_404_when_book_not_found() : void {

        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/{$user->id}/books/99999/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ]);

        $response->assertStatus(404);
    }
    
    public function test_returns_404_when_user_not_found() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/users/99999/books/{$book->id}/reviews", [
            'add_date' => now()->format('Y-m-d'),
            'rating' => 5,
        ]);

        $response->assertStatus(404);
    }
}