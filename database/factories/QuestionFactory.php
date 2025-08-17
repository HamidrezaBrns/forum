<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
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
            'title' => str(fake()->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn() => fake()->realText(1250))->join(PHP_EOL . PHP_EOL),
            'votes_count' => 0,
        ];
    }

    public function withFixture(): self
    {
        $questions = static::getFixtures()
            ->map(fn(string $contents) => str($contents)->explode("\n", 2))
            ->map(fn(Collection $parts) => [
                'title' => str($parts[0])->trim()->after('# '),
                'body' => str($parts[1])->trim()->markdown([
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                    'max_nesting_level' => 5,
                ]),
            ]);

        return $this->sequence(...$questions);
    }

    private static function getFixtures(): Collection
    {
        return once(fn() => collect(File::files(database_path('factories/fixtures/questions')))
            ->map(fn(SplFileInfo $fileInfo) => $fileInfo->getContents()));
    }
}
