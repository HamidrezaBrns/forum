<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    protected function getSubjectData(string $subject_type)
    {
        return match ($subject_type) {
            'question' => QuestionResource::make($this->whenLoaded('subject')),
            'answer' => AnswerResource::make($this->whenLoaded('subject')),
            'comment' => CommentResource::make($this->whenLoaded('subject')),
            'vote' => VoteResource::make($this->whenLoaded('subject')),
            default => null,
        };
//        return match ($subject_type) {
//            'question' => [
//                'id' => $this->subject->id,
//                'title' => $this->subject->title,
//                'body' => $this->subject->body,
//            ],
//
//            'answer' => [
//                'id' => $this->subject->id,
//                'body' => $this->subject->body,
//                'question' => [
//                    'id' => $this->subject->question->id,
//                    'title' => $this->subject->question->title,
//                ],
//            ],
//
//            'comment' => [
//                'id' => $this->subject->id,
//                'body' => $this->subject->body,
//                'commentable_id' => $this->subject->commentable_id,
//                'commentable_type' => $this->subject->commentable_type,
//                'commentable' => match ($this->subject->commentable_type) {
//                    'question' => [
//                        'id' => $this->subject->commentable?->id,
//                        'title' => $this->subject->commentable?->title,
//                    ],
//                    'answer' => [
//                        'id' => $this->subject->commentable?->id,
//                        'question' => [
//                            'id' => $this->subject->commentable?->question->id,
//                            'title' => $this->subject->commentable?->question->title,
//                        ]
//                    ]
//                },
//            ],
//
//            'vote' => [
//                'id' => $this->subject->id,
//                'is_upvote' => $this->subject->is_upvote,
//                'votable_id' => $this->subject->votable_id,
//                'votable_type' => $this->subject->votable_type,
//                'votable' => match ($this->subject->votable_type) {
//                    'question' => [
//                        'id' => $this->subject->votable->id,
//                        'title' => $this->subject->votable->title,
//                        'body' => $this->subject->votable->body,
//                    ],
//                    'answer' => [
//                        'id' => $this->subject->votable->id,
//                        'body' => $this->subject->votable->body,
//                        'question' => [
//                            'id' => $this->subject->votable->question->id,
//                            'title' => $this->subject->votable->question->title,
//                        ],
//                    ],
//                    default => null,
//                },
//            ],
//
//            default => null
//        };
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

            'type' => $this->type,
            'subject_id' => $this->subject_id,
            'subject_type' => $this->subject_type,

            'subject' => $this->whenLoaded('subject', fn() => $this->getSubjectData($this->subject_type)),

            'created_at' => $this->created_at,
        ];
    }
}
