<?php

namespace App\Http\Controllers\Api;

use App\Actions\UpdateBookProgressAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\FindBookRequest;
use App\Http\Requests\SaveBookRequest;
use App\Http\Requests\UpdateBookProgressRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BooksResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Get paginated list of Books.
     */
    public function index(Request $request): BooksResource
    {
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $page = $request->has('page') ? $request->page : 1;

        $books = $request->user()->books()->latest()
            ->simplePaginate(perPage: $perPage, page: $page);

        return new BooksResource($books);
    }

    /**
     * Store a new Book.
     */
    public function store(SaveBookRequest $request): BookResource
    {
        $inputs = $request->validated();
        $book = Book::create($inputs);

        return new BookResource($book);
    }

    /**
     * Get a Book.
     */
    public function show(FindBookRequest $request): BookResource
    {
        $book = Book::where($request->validated())->firstOrFail();

        return new BookResource($book);
    }

    /**
     * Update a Book.
     */
    public function update(SaveBookRequest $request): BookResource
    {
        $book = Book::where($request->only('id', 'user_id'))->firstOrFail();
        $book->update($request->validated());

        return new BookResource($book);
    }

    /**
     * Update reading progress of a Book.
     */
    public function progress(
        UpdateBookProgressRequest $request,
        UpdateBookProgressAction $action
    ): BookResource {
        $book = Book::where($request->only('id', 'user_id'))->firstOrFail();
        $lastPage = $request->last_page;
        $updatedBook = $action->execute($book, $lastPage);

        return new BookResource($updatedBook);
    }

    /**
     * Delete a Book.
     */
    public function destroy(FindBookRequest $request): void
    {
        $book = Book::where($request->validated())->firstOrFail();
        $book->delete();
    }
}
