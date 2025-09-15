<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
{
    protected function getVotableData()
    {
        return match ($this->votable_type) {
            'question' => QuestionResource::make($this->whenLoaded('votable')),
            'answer' => AnswerResource::make($this->whenLoaded('votable')),
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
            'is_upvote' => $this->is_upvote,

            'votable_type' => $this->votable_type,
            'votable' => $this->whenLoaded('votable', fn() => $this->getVotableData()),
        ];
    }
}
