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

}
