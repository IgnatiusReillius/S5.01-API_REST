<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(ReviewRequest $request, string $id_user, string $id_book) : JsonResponse {

        User::findOrFail($id_user);
        Book::findOrFail($id_book);

        $this->authorize('create', [Review::class, (int)$id_user]);

        if (Review::where('id_user', $id_user)->where('id_book', $id_book)->exists()) {
            return response()->json([
                'message' => 'Ya has reseñado este libro. Usa PUT para actualizarlo.'
            ], 409);
        }

        $review = Review::create([
            'id_user' => $id_user,
            'id_book' => $id_book,
            'add_date' => $request->add_date ?? now(),
            'read_date' => $request->read_date,
            'comment' => $request->comment,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'data' => [
                'id_user' => $review->id_user,
                'id_book' => $review->id_book,
                'add_date' => $review->add_date->format('Y-m-d'),
                'read_date' => $review->read_date?->format('Y-m-d'),
                'comment' => $review->comment,
                'rating' => $review->rating,
                'created_at' => $review->created_at,
            ],
            'message' => 'Reseña creada exitosamente'
        ], 201);
    }
}
