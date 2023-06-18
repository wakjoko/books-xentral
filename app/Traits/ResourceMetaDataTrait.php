<?php

namespace App\Traits;

use App\Support\HttpApiFormat;
use Symfony\Component\HttpFoundation\Response;

trait ResourceMetaDataTrait
{
    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with($request): array
    {
        return (new HttpApiFormat(data: $this->with))->toArray();
    }

    /**
     * Customize the response for a request.
     */
    final public function withResponse($request, $response): void
    {
        $statusCode = $response->getStatusCode();

        $this->with['status'] = $statusCode;
        $this->with['title'] = Response::$statusTexts[$statusCode];
    }
}
