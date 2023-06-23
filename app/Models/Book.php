<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes, HasFactory;

    public const STATUSES = BookStatus::ENUMS;

    public $fillable = [
        'user_id',
        'title',
        'author',
        'genre',
        'total_pages',
        'status_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BookStatus::class, 'status_id');
    }

    public function readingProgresses(): HasMany
    {
        return $this->hasMany(ReadingProgress::class, 'book_id');
    }

    protected function lastPageRead(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->readingProgresses()->max('last_page') ?? 0,
        );
    }
}
