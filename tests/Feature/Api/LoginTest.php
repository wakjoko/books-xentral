<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Tests\Traits\WithUser;
use Illuminate\Support\Str;
use App\Enums\TokenStatusEnum;
use Illuminate\Support\Carbon;
use Illuminate\Cache\RateLimiter;
use App\Http\Requests\LoginRequest;
use App\Models\PersonalAccessToken;
use Database\Factories\UserFactory;
use Tests\Traits\ResourceAssertion;
use Tests\Traits\ResourceStructure;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use ResourceAssertion;
    use ResourceStructure;
    use WithFaker;
    use WithUser;

    private array $tokenStructure = [
        'name',
        'abilities',
        'expires_at',
        'type',
        'plain_text',
        'status',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpUser();
    }

    /**
     * @test
     */
    public function itShouldReturnASuccessfulResponseIfTheGivenDataIsCorrect(): void
    {
        $token = PersonalAccessToken::factory()->make();
        $expiresAt = $token->expires_at instanceof Carbon ? $token->expires_at->format('Y-m-d') : '';

        $response = $this->postJson(uri: route('login'), data: [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_TEXT_PASSWORD,
            'token_name' => $token->name,
            'expires_at' => $expiresAt,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'token' => $this->tokenStructure,
        ]);

        $response->assertJson(fn (AssertableJson $json): AssertableJson => $json
            ->where(key: 'token.name', expected: $token->name)
            ->whereContains(key: 'token.abilities', expected: '*')
            ->where(key: 'token.type', expected: 'Bearer')
            ->where(key: 'token.status', expected: TokenStatusEnum::Active->value)
            ->etc());

        $actualExpiresAt = $response->json('token.expires_at');

        $this->assertStringContainsString(
            needle: $expiresAt,
            haystack: $actualExpiresAt,
        );
        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_CREATED);
        $this->assertDatabaseHas(table: 'personal_access_tokens', data: [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->user->id,
            'name' => $token->name,
        ]);
    }

    /**
     * @test
     */
    public function itShouldReturnAnUnprocessableResponseIfTheGivenDataIsIncorrect(): void
    {
        $response = $this->postJson(uri: route('login'), data: [
            'email' => $this->faker->email,
            'password' => $this->faker->randomAscii,
            'token_name' => $this->faker->word,
            'expires_at' => 
                Carbon::createFromInterface($this->faker->dateTimeBetween('today', '+1 month'))
                    ->format('d-m-Y'),  // wrong date format
        ]);

        $response->assertUnprocessable();
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function itShouldReturnATooManyRequestsResponseIfConsecutiveFailedLoginAttemptsOverTheRateLimiter(): void
    {
        /** @var RateLimiter $rateLimiter */
        $rateLimiter = $this->app->make(abstract: RateLimiter::class);
        $throttleKey = Str::lower("{$this->user->email}|") . Request::ip();

        collect(range(1, LoginRequest::MAX_ATTEMPTS))->each(function () use ($rateLimiter, $throttleKey): void {
            $this->app->call(callback: [$rateLimiter, 'hit'], parameters: ['key' => $throttleKey]);
        });

        /** @var PersonalAccessToken $token */
        $token = PersonalAccessToken::factory()->make();
        $expiresAt = $token->expires_at instanceof Carbon ? $token->expires_at->format('Y-m-d') : '';

        $response = $this->postJson(uri: route('login'), data: [
            'email' => $this->user->email,
            'password' => UserFactory::DEFAULT_PLAIN_TEXT_PASSWORD,
            'token_name' => $token->name,
            'expires_at' => $expiresAt,
        ]);

        $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
        $response->assertJsonStructure([
            ...$this->resourceMetaDataStructure,
            'detail',
        ]);

        $this->assertResourceMetaData(response: $response, statusCode: Response::HTTP_TOO_MANY_REQUESTS);
    }
}
