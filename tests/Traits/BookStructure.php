<?php

namespace Tests\Traits;

trait BookStructure
{
    private array $bookStructure = [
        'title',
        'author',
        'genre',
        'total_pages',
        'status_id',
        'last_page_read',
        'reading_progress',
    ];
}
