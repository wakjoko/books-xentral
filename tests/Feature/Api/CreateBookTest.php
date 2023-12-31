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
use Tests\Traits\WithUser;

class CreateBookTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use BookStructure;
    use WithFaker;
    use WithUser;
    use WithBookStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
        $this->createBookStatuses();
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfTheGivenDataIsCorrect(): void
    {
        $book = [
            'title' => $this->faker->sentence(5),
            'author' => $this->faker->name(5),
            'genre' => $this->faker->sentence(5),
            'total_pages' => $this->faker->numberBetween(10, 100),
        ];

        $response = $this->actingAs($this->user)
            ->postJson(
                uri: route('books.store'),
                data: $book
            );

        $response->assertCreated();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'book' => $this->bookStructure,
        ]);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnprocessableResponseIfTheGivenDataIsIncorrect(): void
    {
        $book = [
            'title' => null,
            'author' => null,
            'genre' => null,
            'total_pages' => null,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(
                uri: route('books.store'),
                data: $book
            );

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
