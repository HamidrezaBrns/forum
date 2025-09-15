<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $last7Days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        return Inertia::render('Admin/Dashboard', [
            'trends' => [
                'users' => $this->buildTrend(User::class, $last7Days),
                'questions' => $this->buildTrend(Question::class, $last7Days),
                'answers' => $this->buildTrend(Answer::class, $last7Days),
            ],

            'stats' => [
                'usersCount' => User::count(),
                'questionsCount' => Question::count(),
                'answersCount' => Answer::count(),
                'commentsCount' => Comment::count(),
                'tagsCount' => Tag::count(),
                'votesCount' => Vote::count(),
            ],

            'latest' => [
                'users' => User::query()
                    ->select(['id', 'username', 'email', 'name', 'created_at'])
                    ->latest()->latest('id')
                    ->take(5)
                    ->get(),

                'tags' => Tag::query()
                    ->select(['id', 'name', 'description', 'created_at'])
                    ->latest()->latest('id')
                    ->take(5)
                    ->get(),

                'questions' => Question::query()
                    ->with('user:id,username')
                    ->latest()->latest('id')
                    ->take(5)
                    ->get(),
            ],
        ]);
    }

    /**
     * Build a trend collection for the given model and dates.
     *
     * @param class-string<Model> $model
     * @param Collection $dates
     * @return Collection
     */
    private function buildTrend(string $model, Collection $dates): Collection
    {
        return $dates->map(fn($day) => [
            'date' => $day,
            'count' => $model::whereDate('created_at', $day)->count(),
        ]);
    }
}
