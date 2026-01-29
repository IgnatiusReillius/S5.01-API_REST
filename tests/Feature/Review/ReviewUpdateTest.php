<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ReviewUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 3,
            'comment' => 'Comentario original',
        ]);

        Passport::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'rating' => 5,
            'comment' => 'Comentario actualizado!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'rating' => 5,
                    'comment' => 'Comentario actualizado!',
                ],
                'message' => 'Reseña actualizada exitosamente'
            ]);

        $this->assertDatabaseHas('reviews', [
            'id_user' => $user->id,
            'id_book' => $book->id,
            'rating' => 5,
            'comment' => 'Comentario actualizado!',
        ]);
    }

    public function test_admin_can_update_any_user_review() : void {

        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 3,
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_cannot_update_another_user_review() : void {

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user2->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 3,
        ]);

        Passport::actingAs($user1);

        $response = $this->putJson("/api/users/{$user2->id}/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_returns_404_when_updating_non_existent_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(404);
    }

    public function test_cannot_update_review_with_invalid_rating() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 3,
        ]);

        Passport::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'rating' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_unauthenticated_user_cannot_update_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 3,
        ]);

        $response = $this->putJson("/api/users/{$user->id}/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(401);
    }
}