<?php

namespace Database\Seeders;

use App\Models\Comment;
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
            ->create()
            ->each(function (Question $question) use ($users, $tags) {
                $question->tags()->attach($tags->random(rand(1, 5)));

                Comment::factory(rand(0, 10))->recycle($users)->create([
                    'commentable_id' => $question,
                ]);
            });

        $answers = Answer::factory(200)
            ->recycle($users)
            ->recycle($questions)
            ->create()
            ->each(fn(Answer $answer) => Comment::factory(rand(0, 5))->recycle($users)->create([
                'commentable_id' => $answer,
            ]));

        $hamidreza = User::factory()
            ->has(Question::factory(5)->withFixture())
            ->has(Answer::factory(20)->recycle($questions))
            ->has(Vote::factory()->forEachSequence(
                ...$questions->random(100)->map(fn(Question $question) => ['votable_id' => $question]),
                ...$answers->random(200)->map(fn(Answer $answer) => ['votable_id' => $answer]),
            ))
            ->create([
                'username' => 'HamidrezaBRNS',
                'email' => 'test@example.com',
                'name' => 'Hamidreza Barnousi',
                'is_admin' => true,
            ]);
    }
}
