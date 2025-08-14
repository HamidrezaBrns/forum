<?php

use App\Models\Answer;

use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('votes.store', ['question', 1]))
        ->assertRedirect(route('login'));
});

it('allowing upvote a votable', function (Model $votable) {
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('votes.store', [$votable->getMorphClass(), $votable->id, 'is_upvote' => 1]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas(Vote::class, [
        'user_id' => $user->id,
        'votable_id' => $votable->id,
        'votable_type' => $votable->getMorphClass(),
        'is_upvote' => 1,
    ]);

    $this->assertEquals(1, $votable->refresh()->votes_count);

})->with([
    fn() => Question::factory()->create(),
    fn() => Answer::factory()->create(),
]);

it('allowing downvote a votable', function (Model $votable) {
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('votes.store', [$votable->getMorphClass(), $votable->id, 'is_upvote' => 0]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas(Vote::class, [
        'user_id' => $user->id,
        'votable_id' => $votable->id,
        'votable_type' => $votable->getMorphClass(),
        'is_upvote' => 0,
    ]);

    $this->assertEquals(-1, $votable->refresh()->votes_count);

})->with([
    fn() => Question::factory()->create(),
    fn() => Answer::factory()->create(),
]);

it('allowing downvote an upvoted', function () {
    $user = User::factory()->create();
    $votable = Question::factory()->create();
    $vote = Vote::factory()->for($user)->for($votable, 'votable')->create(['is_upvote' => 1]);

    $this->assertEquals(1, $votable->refresh()->votes_count);

    actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('votes.store', [$votable->getMorphClass(), $votable->id, 'is_upvote' => 0]))
        ->assertRedirect(route('dashboard'));

    $this->assertEquals(-1, $votable->refresh()->votes_count);
});

it('prevent upvoting something you already upvoted', function () {
    $vote = Vote::factory()->create(['is_upvote' => 1]);

    $votable = $vote->votable;

    actingAs($vote->user)
        ->post(route('votes.store', [$votable->getMorphClass(), $votable->id, 'is_upvote' => 1]))
        ->assertForbidden();
});

it('prevent downvoting something you already downvoted', function () {
    $vote = Vote::factory()->create(['is_upvote' => 0]);

    $votable = $vote->votable;

    actingAs($vote->user)
        ->post(route('votes.store', [$votable->getMorphClass(), $votable->id, 'is_upvote' => 0]))
        ->assertForbidden();
});

it('only allows voting supported models', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('votes.store', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
    actingAs(User::factory()->create())
        ->post(route('votes.store', ['foo', 1]))
        ->assertNotFound();
});
