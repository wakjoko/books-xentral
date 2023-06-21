<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResourceAssertion
{
    private function assertResourceMetaData(TestResponse $response, int $statusCode): void
    {
        $response->assertJson([
            'status' => $statusCode,
            'title' => Response::$statusTexts[$statusCode],
        ]);
    }
}
