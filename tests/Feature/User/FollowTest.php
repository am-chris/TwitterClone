<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::flushall();
    }

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

        $this->assertTrue($user->followingUser($user2));
    }

    /** @test */
    public function user_can_unfollow_another_user()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertTrue($user->followingUser($user2));

        $this->actingAs($user)
            ->json('DELETE', route('users.follows.destroy', $user2->id));

        $this->assertFalse($user->followingUser($user2));
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

        $this->assertFalse($user->followingUser($user));
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

        $this->assertTrue($user->followingUser($user2));
    }

    /** @test */
    public function user_can_have_multiple_followers()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user3->id), [
                'user_id' => $user3->id,
                'current_user_id' => $user->id,
            ]);

        $this->actingAs($user2)
            ->json('POST', route('users.follows.store', $user3->id), [
                'user_id' => $user3->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertCount(2, $user3->followers());
    }
}
