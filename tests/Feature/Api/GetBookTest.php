<?php

namespace Tests\Feature\Api;

use App\Traits\WithBookStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\Traits\BookStructure;
use Tests\Traits\ResourceAssertion;
use Tests\Traits\ResourceStructure;
use Tests\Traits\WithBooks;
use Tests\Traits\WithUser;

class GetBookTest extends TestCase
{
    use BookStructure;
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;
    use WithBooks;
    use WithBookStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->createBookStatuses();
        $this->createBook();
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfAuthenticatedAndResourceAvailable(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route(name: 'books.show', parameters: ['id' => $this->book->id]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnauthorizedResponseIfUnauthenticated(): void
    {
        $response = $this->get(route(name: 'books.show', parameters: ['id' => $this->book->id]));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function itShouldReturnANotFoundResponseIfResourceNotAvailable(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route(name: 'books.show', parameters: [
                'id' => $this->faker->md5(),   // wrong book id
            ]));

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_NOT_FOUND);
    }
}
