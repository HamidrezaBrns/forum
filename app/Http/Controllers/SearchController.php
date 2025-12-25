<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\QuestionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search_q = $request->query('q');

        $questions = Question::with(['user', 'tags'])->whereNot('status', QuestionStatus::DRAFT)
            ->when($search_q, function (Builder $query) use ($search_q) {
                [$tags, $text] = $this->parseSearchQuery($search_q);

                if (!empty($tags)) {
                    foreach ($tags as $tag) {
                        $query->whereHas('tags', fn(Builder $q) => $q->where('name', $tag));
                    }
                }

                if ($text) {
                    $query->whereAny(['title', 'body'], 'LIKE', "%$text%");
                }
            })
            ->latest()
            ->latest('id')
            ->paginate()
            ->withQueryString();

        return inertia('SearchResults', [
            'questions' => fn() => QuestionResource::collection($questions),
            'query' => $search_q,
        ]);

    }

    /**
     * Extract tags `[tag]` and plain text from query string.
     */
    protected function parseSearchQuery(string $search_q): array
    {
        preg_match_all('#\[([^\]]+)\]#', $search_q, $matches);

        $tags = array_map('strtolower', $matches[1] ?? []);
        $text = trim(preg_replace('#\[([^\]]+)\]#', '', $search_q));

        return [$tags, $text];
    }
}
