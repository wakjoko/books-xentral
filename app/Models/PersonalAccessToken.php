<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Scopes\NotExpiredScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    /**
     * Set expiry date format.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<mixed, mixed>
     */
    public function expiresAt(): Attribute
    {
        return new Attribute(
            set: fn (Carbon $value): Carbon => $value->setHour(23)->setMinute(59)->setSecond(59),
        );
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        self::addGlobalScope(new NotExpiredScope());
    }
}
