<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vote>
 */
class VoteFactory extends Factory
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
            'votable_type' => $this->votableType(...),
            'votable_id' => Question::factory(),
            'is_upvote' => fake()->boolean,
        ];
    }

    protected function votableType($values)
    {
        $type = $values['votable_id'];
        $modelName = $type instanceof Factory
            ? $type->modelName()
            : $type::class;

        return (new $modelName)->getMorphClass();
    }
}
