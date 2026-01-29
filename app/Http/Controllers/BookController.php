<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index() : JsonResponse {

        $this->authorize('viewAny', Book::class);
        
        $books = Book::all();

        return response()->json(['data' => $books], 200);
    }

    public function store(BookRequest $request) : JsonResponse {

        $this->authorize('create', Book::class);

        $book = Book::create($request->validated());

        return response()->json(['data' => $book], 201);
    }

    public function find(string $id) : JsonResponse {

        $book = Book::findOrFail($id);
        
        $this->authorize('view', $book);

        return response()->json(['data' => $book], 200);
    }

    public function update(BookUpdateRequest $request, string $id) : JsonResponse {

        $book = Book::findOrFail($id);
        
        $this->authorize('update', $book);

        $book->update($request->validated());

        return response()->json(['data' => $book], 200);
    }

    public function destroy(string $id) : JsonResponse {

        $book = Book::findOrFail($id);
        
        $this->authorize('delete', $book);

        $book->delete();

        return response()->json(null, 204);
    }

    public function destroyAll() : JsonResponse {

        $this->authorize('deleteAll', Book::class);

        Book::query()->delete();

        return response()->json(null, 204);
    }
}
