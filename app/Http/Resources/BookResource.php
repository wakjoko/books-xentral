<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BookResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     */
    public static $wrap = 'book';

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Book|static $this */
        return [
            'title' => $this->title,
            'author' => $this->author,
            'genre' => $this->genre,
            'total_pages' => $this->total_pages,
            'status_id' => $this->status_id,
            'last_page_read' => $this->last_page_read,
            'reading_progress' => ReadingProgressResource::collection($this->readingProgresses),
        ];
    }
}
