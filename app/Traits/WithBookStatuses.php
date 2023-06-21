<?php

namespace App\Traits;

use App\Models\BookStatus;
use Illuminate\Support\Facades\Schema;

trait WithBookStatuses
{
    /**
     * Store predefined enums from model into database
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
}
