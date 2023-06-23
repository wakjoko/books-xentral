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

class UpdateBookProgressTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use BookStructure;
    use WithFaker;
    use WithUser;
    use WithBooks;
    use WithBookStatus;

    protected $readingProgress;

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
    // public function itShouldGetToReadBookStatusIfNoReadingProgress(): void
    // {}

    /**
     * @test
     */
    public function itShouldGetReadingBookStatusIfLastPageReadIsLessThanBookTotalPages(): void
    {
        /**
         * add reading progress
         */
        $nextLastPageRead = $this->faker->numberBetween(
            1,
            $this->book->total_pages - $this->faker->randomDigitNotZero()
        );

        $newProgress = [
            'last_page' => $nextLastPageRead,
        ];

        $addedProgress = $this->actingAs($this->user)
            ->putJson(
                uri: route('books.progress', ['id' => $this->book->id]),
                data: $newProgress
            );

        $addedProgress->assertOk();
        $addedProgress->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        /**
         * verify reading progress history
         */
        $response = $this->actingAs($this->user)
            ->get(route(name: 'books.show', parameters: ['id' => $this->book->id]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);

        // TODO: assert db books.status_id should be "Reading"
        // TODO: assert db books.last_page vs the last reading_progress.total_pages
    }

    /**
     * @test
     */
    public function itShouldGetReadBookStatusIfLastPageReadIsEqualsToBookTotalPages(): void
    {
        /**
         * add reading progress
         */
        $newProgress = [
            'last_page' => $this->book->total_pages,
        ];

        $addedProgress = $this->actingAs($this->user)
            ->putJson(
                uri: route('books.progress', ['id' => $this->book->id]),
                data: $newProgress
            );

        $addedProgress->assertOk();
        $addedProgress->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        /**
         * verify reading progress history
         */
        $response = $this->actingAs($this->user)
            ->get(route(name: 'books.show', parameters: ['id' => $this->book->id]));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);

        // TODO: assert db books.status_id should be "Read"
        // TODO: assert db books.last_page vs the last reading_progress.total_pages
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnprocessableResponseIfTheGivenDataIsIncorrect(): void
    {
        $newProgress = [
            'last_page' => null,
        ];

        $response = $this->actingAs($this->user)
            ->putJson(
                uri: route('books.progress', ['id' => $this->book->id]),
                data: $newProgress
            );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
