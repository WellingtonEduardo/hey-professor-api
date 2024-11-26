<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

/**
 * @property-read int $votes_sum_like
 * @property-read int $votes_sum_unlike
 */
class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;
    use SoftDeletes;

    /**

     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     * @return HasMany<Vote, $this>
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', '=', 'published');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {

        return $query->when($search, fn (Builder $q) => $q->where('question', 'like', "%{$search}%"));
    }
}
