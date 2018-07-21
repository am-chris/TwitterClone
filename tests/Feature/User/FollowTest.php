<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_follow_another_user()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertDatabaseHas('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_unfollow_another_user()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('DELETE', route('users.follows.destroy', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_cant_follow_themselves()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user->id), [
                'user_id' => $user->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user->id,
            'follower_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_only_follow_another_user_once()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertDatabaseHas('follows', [
            'id' => 1,
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('follows', [
            'id' => 2,
            'user_id' => $user->id,
        ]);
    }
}
