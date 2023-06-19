<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Tests\Traits\WithUser;
use Tests\Traits\ResourceAssertion;
use Tests\Traits\ResourceStructure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowLoggedInUserTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithUser;

    private array $userStructure = [
        'name',
        'email',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfAuthenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('user'));

        $response->assertOk();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'user' => $this->userStructure,
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnauthorizedResponseIfUnauthenticated(): void
    {
        $response = $this->get(route('user'));

        $response->assertUnauthorized();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNAUTHORIZED);
    }
}
