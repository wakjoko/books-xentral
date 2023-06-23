<?php

namespace App\Http\Resources;

use App\Traits\ResourceMetaDataTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PersonalAccessTokenResource extends JsonResource
{
    use ResourceMetaDataTrait;

    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'token';

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\PersonalAccessToken|static $this */
        return [
            'name' => $this->name,
            'abilities' => $this->abilities,
            'expires_at' => $this->expires_at,
        ];
    }
}
