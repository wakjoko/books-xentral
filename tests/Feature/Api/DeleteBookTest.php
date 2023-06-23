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

class DeleteBookTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use BookStructure;
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
    public function itShouldReturnASuccessfulResponseIfTheGivenDataIsCorrect(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson(
                uri: route('books.destroy', ['id' => $this->book->id])
            );

        $response->assertNoContent(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function itShouldReturnNotFoundResponseIfTheGivenDataIsIncorrect(): void
    {
        $response = $this->actingAs($this->user)
            ->deleteJson(
                uri: route('books.destroy', ['id' => $this->faker->numberBetween(10, 100)])
            );

        $response->assertNotFound();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_NOT_FOUND);
    }
}
