<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'commentable_type' => $this->commentableType(...),
            'commentable_id' => Question::factory(),
            'body' => fake()->realText(),
            'created_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    protected function commentableType($values)
    {
        $type = $values['commentable_id'];
        $modelName = $type instanceof Factory
            ? $type->modelName()
            : $type::class;

        return (new $modelName)->getMorphClass();
    }
}
