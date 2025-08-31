<?php

namespace App\Http\Resources;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class AnswerResource extends JsonResource
{
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
            'question' => QuestionResource::make($this->whenLoaded('question')),
            'body' => $this->body,
            'comments_count' => $this->comments_count, // by query
            'votes_count' => Number::abbreviate($this->votes_count), // table field
            'vote' => $this->when($request->user(), fn() => VoteResource::make($this->votes()->whereBelongsTo($request->user())->first())), //
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can' => [
                'update' => $request->user()?->can('update', $this->resource),
                'delete' => $request->user()?->can('delete', $this->resource),
                'vote' => $request->user()?->can('create', [Vote::class, $this->resource]),
                'accept' => $request->user()?->can('accept', $this->resource),
            ],
        ];
    }
}
