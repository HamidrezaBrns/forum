<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\HasOptionalAttributes;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    use HasOptionalAttributes;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->getMorphClass(),
            'user' => UserResource::make($this->whenLoaded('user')),
            'title' => $this->title,
            'body' => $this->body,
            'tags' => $this->whenLoaded('tags', fn() => $this->tags->pluck('name')),
            'accepted_answer_id' => $this->accepted_answer_id,
            'answers_count' => $this->answers_count, // by query with count
            'comments_count' => $this->comments_count, // by query with count
            'votes_count' => Number::abbreviate($this->votes_count), // table field
            'views_count' => Number::abbreviate(views($this->resource)->count()),
            'vote' => $this->when(
                $this->hasAttribute('user-vote') && $request->user(),
                fn() => VoteResource::make($this->votes()->whereBelongsTo($request->user())->first())
            ), //
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can' => $this->when($this->hasAttribute('permissions'),
                fn() => [
                    'update' => $request->user()?->can('update', $this->resource),
                    'delete' => $request->user()?->can('delete', $this->resource),
                    'vote' => $request->user()?->can('create', [Vote::class, $this->resource]),
                ]
            ),
        ];
    }
}
