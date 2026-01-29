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

    public function getAllReviews() : JsonResponse {

        $this->authorize('viewAny', Review::class);

        $reviews = Review::with(['user', 'book'])
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'id_user' => $review->id_user,
                    'user_name' => $review->user->name,
                    'id_book' => $review->id_book,
                    'book_title' => $review->book->title,
                    'book_author' => $review->book->author,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'add_date' => $review->add_date->format('Y-m-d'),
                    'read_date' => $review->read_date?->format('Y-m-d'),
                    'created_at' => $review->created_at,
                ];
            });

        return response()->json(['data' => $reviews]);
    }

    public function getBookReviews(string $id_book) : JsonResponse {

        Book::findOrFail($id_book);

        $this->authorize('viewBook', Review::class);

        $reviews = Review::where('id_book', $id_book)
            ->with('user')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'id_user' => $review->id_user,
                    'user_name' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'add_date' => $review->add_date->format('Y-m-d'),
                    'read_date' => $review->read_date?->format('Y-m-d'),
                    'created_at' => $review->created_at,
                ];
            });

        return response()->json(['data' => $reviews]);
    }

    public function getUserReviews(string $id_user) : JsonResponse {

        User::findOrFail($id_user);

        $this->authorize('viewUser', [Review::class, (int)$id_user]);

        $reviews = Review::where('id_user', $id_user)
            ->with('book')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'id_book' => $review->id_book,
                    'book_title' => $review->book->title,
                    'book_author' => $review->book->author,
                    'add_date' => $review->add_date->format('Y-m-d'),
                    'read_date' => $review->read_date?->format('Y-m-d'),
                    'comment' => $review->comment,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at,
                    'updated_at' => $review->updated_at,
                ];
            });

        return response()->json(['data' => $reviews]);
    }
    
    public function show(string $id_user, string $id_book) : JsonResponse {

        User::findOrFail($id_user);
        Book::findOrFail($id_book);

        $review = Review::where('id_user', $id_user)
            ->where('id_book', $id_book)
            ->with(['user', 'book'])
            ->firstOrFail();

        $this->authorize('view', $review);

        return response()->json([
            'data' => [
                'id' => $review->id,
                'id_user' => $review->id_user,
                'id_book' => $review->id_book,
                'book_title' => $review->book->title,
                'add_date' => $review->add_date->format('Y-m-d'),
                'read_date' => $review->read_date?->format('Y-m-d'),
                'comment' => $review->comment,
                'rating' => $review->rating,
                'created_at' => $review->created_at,
                'updated_at' => $review->updated_at,
            ]
        ]);
    }
}
