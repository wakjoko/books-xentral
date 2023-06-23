<?php

namespace Tests\Traits;

use App\Models\Book;
use Illuminate\Support\Collection;

trait WithBooks
{
    private Collection $books;

    private Book $book;

    private int $status;

    /**
     * Create a book for $user.
     * $user should be defined in caller class
     */
    private function createBook(int $status = null): void
    {
        $this->setStatusIfNotProvided($status);
        $this->book = Book::factory()
            ->create([
                'user_id' => $this->user->id,
                'status_id' => $this->status,
            ]);
    }

    /**
     * Create many books for $user based on $totalBooks.
     * $user should be defined in caller class
     */
    private function createManyBooks(int $totalBooks = 1, int $status = null): void
    {
        $this->setStatusIfNotProvided($status);
        $this->books = Book::factory($totalBooks)
            ->create([
                'user_id' => $this->user->id,
                'status_id' => $this->status,
            ]);
    }

    private function setStatusIfNotProvided(int $status = null): void
    {
        if (! $status) {
            $this->status = array_key_first(Book::STATUSES);
        }
    }
}
