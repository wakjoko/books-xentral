<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookStatus extends Model
{
    use SoftDeletes, HasFactory;

    public const ENUMS = [
        1 => 'To Read',
        2 => 'Reading',
        3 => 'Read',
    ];

    public $fillable = [
        'name',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'status_id');
    }
}
