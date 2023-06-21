<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class BooksResource extends ResourceCollection
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     */
    public static $wrap = 'books';
}
