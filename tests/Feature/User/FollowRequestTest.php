<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowRequestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::flushall();
    }

    /** @test */
    public function user_follow_turns_into_follow_request_if_user_profile_is_private()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create(['private' => 1]);

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->assertFalse($user->followingUser($user2));

        $this->assertCount(1, $user2->followRequests());
    }

    /** @test */
    public function private_user_can_approve_follow_requests()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create(['private' => 1]);

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->actingAs($user2)
            ->json('POST', route('users.follow_requests.approve', $user->id), [
                'user_id' => $user->id,
                'current_user_id' => $user2->id,
            ]);

        $this->assertTrue($user->followingUser($user2));

        $this->assertFalse($user->followRequested($user2));
    }

    /** @test */
    public function private_user_can_deny_follow_requests()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create(['private' => 1]);

        $this->actingAs($user)
            ->json('POST', route('users.follows.store', $user2->id), [
                'user_id' => $user2->id,
                'current_user_id' => $user->id,
            ]);

        $this->actingAs($user2)
            ->json('POST', route('users.follow_requests.deny', $user->id), [
                'user_id' => $user->id,
                'current_user_id' => $user2->id,
            ]);

        $this->assertFalse($user->followingUser($user2));

        $this->assertFalse($user->followRequested($user2));
    }

    /** @test */
    public function user_can_have_multiple_pending_follow_requests()
    {
        $user = factory(User::class)->create(['private' => 1]);
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $this->actingAs($user2)
            ->json('POST', route('users.follows.store', $user->id), [
                'user_id' => $user->id,
                'current_user_id' => $user2->id,
            ]);

        $this->actingAs($user3)
            ->json('POST', route('users.follows.store', $user->id), [
                'user_id' => $user->id,
                'current_user_id' => $user3->id,
            ]);

        $this->assertCount(2, $user->followRequests());
    }
}
