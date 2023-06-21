<?php

namespace Tests\Traits;

use App\Models\Book;
use Illuminate\Support\Collection;

trait WithBooks
{
    private Collection $books;

    /**
     * Create a book for $user.
     * $user should be defined in caller class
     */
    private function createBook(): void
    {
        $this->books = Book::factory()
            ->createMany([['user_id' => $this->user->id]]);
    }

    /**
     * Create many books for $user based on $totalBooks.
     * $totalBooks and $user should be defined in caller class
     */
    private function createBooks(): void
    {
        $this->books = Book::factory($this->totalBooks)
            ->create(['user_id' => $this->user->id]);
    }
}
