<?php

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    delete(route('votes.destroy', ['question', 1]))
        ->assertRedirect(route('login'));
});

it('allowing downvote a votable', function (Model $votable) {
    $user = User::factory()->create();
    $vote = Vote::factory()->for($user)->for($votable, 'votable')->create(['is_upvote' => 1]);

    $this->assertEquals(1, $votable->refresh()->votes_count);

    actingAs($user)
        ->fromRoute('dashboard')
        ->delete(route('votes.destroy', [$votable->getMorphClass(), $votable->id]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseEmpty(Vote::class);

    $this->assertEquals(0, $votable->refresh()->votes_count);

})->with([
    fn() => Question::factory()->create(),
    fn() => Answer::factory()->create(),
]);

//it('prevent downvoting something you haven\'t voted', function () {
//    $votable = Question::factory()->create();
//
//    actingAs(User::factory()->create())
//        ->delete(route('votes.destroy', [$votable->getMorphClass(), $votable->id]))
//        ->assertForbidden();
//});

it('only allows downvoting supported models', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->delete(route('votes.destroy', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();

});

it('throws a 404 if the type is unsupported', function () {
    actingAs(User::factory()->create())
        ->delete(route('votes.destroy', ['foo', 1]))
        ->assertNotFound();
});
