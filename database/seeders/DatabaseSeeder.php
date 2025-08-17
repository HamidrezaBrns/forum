<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TagSeeder::class);
        $tags = Tag::all();

        $users = User::factory(10)->create();

        $questions = Question::factory(100)
            ->withFixture()
            ->recycle($users)
            ->hasAttached($tags->random(3))
            ->create();

        $answers = Answer::factory(200)->recycle($users)->recycle($questions)->create();

        $hamidreza = User::factory()
            ->has(Question::factory(5)->withFixture())
            ->has(Answer::factory(20)->recycle($questions))
            ->has(Vote::factory()->forEachSequence(
                ...$questions->random(100)->map(fn(Question $question) => ['votable_id' => $question]),
                ...$answers->random(200)->map(fn(Answer $answer) => ['votable_id' => $answer]),
            ))
            ->create([
                'name' => 'Hamidreza',
                'email' => 'test@example.com',
            ]);
    }
}
