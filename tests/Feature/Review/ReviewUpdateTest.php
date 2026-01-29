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
}