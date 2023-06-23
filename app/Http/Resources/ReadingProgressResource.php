<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ReadingProgressResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'reading_progress';

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request)
    {
        return [
            'last_page' => $this->last_page,
            'updated_at' => $this->updated_at,
        ];
    }
}
