<?php

namespace Tests\Feature\Review;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ReviewReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_reviews() : void {

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

        $response = $this->getJson("/api/users/{$user->id}/reviews");

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }
}
