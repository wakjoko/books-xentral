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

class GetPaginatedBooksTest extends TestCase
{
    use BookStructure;
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;
    use WithBooks;
    use WithBookStatus;

    protected int $totalBooks = 100;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->createBookStatuses();
        $this->createManyBooks($this->faker->numberBetween(50, 100));
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfAuthenticated(): void
    {
        $request = [
            'page' => $this->faker->numberBetween(1, 100),
            'perPage' => $this->faker->numberBetween(5, 50),
        ];

        $response = $this->actingAs($this->user)
            ->call(method: 'GET', uri: route('books.index'), parameters: $request);

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->paginationStructure,
            ...$this->resourceMetaDataStructure,
            'books' => [
                '*' => $this->bookStructure,
            ],
        ]);

        /**
         * TODO: assert response data is equals to request perPage
         */
        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnauthorizedResponseIfUnauthenticated(): void
    {
        $response = $this->call(method: 'GET', uri: route('books.index'));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNAUTHORIZED);
    }
}
