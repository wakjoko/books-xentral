<?php

namespace Tests\Traits;

use App\Models\PersonalAccessToken;
use App\Models\User;

trait WithUser
{
    private User $user;

    private function createUser(): void
    {
        PersonalAccessToken::truncate();

        $this->user = User::factory()
            ->has(PersonalAccessToken::factory(), 'tokens')
            ->createOne();
    }
}
