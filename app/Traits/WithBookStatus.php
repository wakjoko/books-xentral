<?php

namespace App\Traits;

use App\Models\BookStatus;
use BadFunctionCallException;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

trait WithBookStatus
{
    /**
     * Store predefined enums from model into database.
     */
    final public function createBookStatuses(): void
    {
        Schema::disableForeignKeyConstraints();
        BookStatus::truncate();

        /**
         * convert enum array to model friendly array
         */
        $statusNames = array_values(BookStatus::ENUMS);
        $statuses = array_map(fn ($value) => ['name' => $value], $statusNames);

        BookStatus::factory()->createMany($statuses);

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Update books.status_id based on books.total_pages vs the last reading_progress.last_page
     *
     * $this->readingProgress and $this->book must be set before using this function.
     */
    private function updateBookStatus(): void
    {
        if (! $this->readingProgress || ! $this->book) {
            throw new BadFunctionCallException(
                self::class.': $this->readingProgress or $this->book should not empty!',
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($this->readingProgress->last_page == $this->book->total_pages) {
            $bookStatus = array_key_last(BookStatus::ENUMS);
        } else {
            $bookStatus = array_key_first(BookStatus::ENUMS) + 1;
        }

        $this->book->update(['status_id' => $bookStatus]);
    }
}
