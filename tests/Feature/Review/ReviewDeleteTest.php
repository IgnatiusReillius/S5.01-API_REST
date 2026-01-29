<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ReviewDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_specific_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 5,
        ]);

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}/books/{$book->id}/reviews");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('reviews', [
            'id_user' => $user->id,
            'id_book' => $book->id,
        ]);
    }

    public function test_admin_can_delete_any_user_specific_review() : void {

        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 5,
        ]);

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$user->id}/books/{$book->id}/reviews");

        $response->assertStatus(204);
    }

    public function test_user_cannot_delete_another_user_review() : void {

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user2->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 5,
        ]);

        Passport::actingAs($user1);

        $response = $this->deleteJson("/api/users/{$user2->id}/books/{$book->id}/reviews");

        $response->assertStatus(403);
    }
    
    public function test_unauthenticated_user_cannot_delete_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 5,
        ]);

        $response = $this->deleteJson("/api/users/{$user->id}/books/{$book->id}/reviews");

        $response->assertStatus(401);
    }

    public function test_returns_404_when_deleting_non_existent_review() : void {

        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}/books/{$book->id}/reviews");

        $response->assertStatus(404);
    }

    public function test_user_can_delete_all_own_reviews() : void {

        $user = User::factory()->create();
        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book1->id,
            'add_date' => now(),
            'rating' => 5,
        ]);

        Review::create([
            'id_user' => $user->id,
            'id_book' => $book2->id,
            'add_date' => now(),
            'rating' => 4,
        ]);

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}/reviews");

        $response->assertStatus(204);

        $this->assertEquals(0, Review::where('id_user', $user->id)->count());
    }
    
    public function test_user_cannot_delete_another_user_all_reviews() : void {

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create();

        Review::create([
            'id_user' => $user2->id,
            'id_book' => $book->id,
            'add_date' => now(),
            'rating' => 5,
        ]);

        Passport::actingAs($user1);

        $response = $this->deleteJson("/api/users/{$user2->id}/reviews");

        $response->assertStatus(403);
    }
}
