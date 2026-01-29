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
}