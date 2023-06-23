<?php

namespace App\Actions;

use App\Models\Book;
use App\Traits\WithBookStatus;

class UpdateBookProgressAction
{
    use WithBookStatus;

    protected $book;

    protected $readingProgress;

    public function execute(Book $book, int $lastPage): Book
    {
        $this->book = $book;
        $this->addReadingProgress($lastPage);
        $this->updateBookStatus();

        return $this->book;
    }

    private function addReadingProgress(int $lastPage): void
    {
        $this->readingProgress = $this->book->readingProgresses()->create([
            'last_page' => $lastPage,
        ]);
    }
}
