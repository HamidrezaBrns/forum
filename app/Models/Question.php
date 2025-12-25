<?php

namespace App\Models;

use App\QuestionStatus;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Question extends Model implements Viewable
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory;
    use InteractsWithViews;
    use SoftDeletes;

    protected $withCount = ['answers', 'comments'];

    protected $casts = [
        'status' => QuestionStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function acceptedAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'accepted_answer_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    // attribute mutator
    protected function title(): Attribute
    {
        return Attribute::set(fn(string $value) => Str::title($value));
    }

    // scopes
    protected function scopeSearchPanel(Builder $query, ?string $term): Builder
    {
        return $query->when(
            $term,
            fn(Builder $query) => $query->whereAny(['title', 'body'], 'LIKE', "%$term%")
                ->orWhereHas('tags', fn(Builder $query) => $query->where('name', 'LIKE', "%$term%"))
                ->orWhereHas('user', fn(Builder $query) => $query->whereAny(['username', 'email'], 'LIKE', "%$term%"))
        );
    }

    protected function scopeFilterPanel(Builder $query, ?string $filter): Builder
    {
        return $query->when(
            $filter,
            fn(Builder $query) => match ($filter) {
                'open' => $query->where('status', 'open'),
                'closed' => $query->where('status', 'closed'),
                'draft' => $query->where('status', 'draft'),
                default => null,
            });
    }

    // helper methods
    public function isOpen(): bool
    {
        return $this->status === QuestionStatus::OPEN;
    }

    public function isClosed(): bool
    {
        return $this->status === QuestionStatus::CLOSED;
    }

    public function isDraft(): bool
    {
        return $this->status === QuestionStatus::DRAFT;
    }

    public function close(): void
    {
        $this->update([
            'status' => QuestionStatus::CLOSED,
            'closed_at' => now(),
        ]);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => QuestionStatus::OPEN,
            'closed_at' => null,
        ]);
    }
}
