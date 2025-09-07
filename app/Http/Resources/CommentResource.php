<?php

namespace App\Http\Resources;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    protected function getCommentableData()
    {
        return match ($this->commentable_type) {
            'question' => QuestionResource::make($this->whenLoaded('commentable')),
            'answer' => AnswerResource::make($this->whenLoaded('commentable')),
            default => null
        };
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'body' => $this->body,

            'commentable_type' => $this->commentable_type,
            'commentable' => $this->whenLoaded('commentable', fn() => $this->getCommentableData()),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'can' => [
//                'update' => $request->user()?->can('update', $this->resource),
                'delete' => $request->user()?->can('delete', $this->resource),
//                'vote' => $request->user()?->can('create', [Vote::class, $this->resource]),
            ],
        ];
    }
}
