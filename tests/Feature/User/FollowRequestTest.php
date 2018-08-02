<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowRequestTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);

        $this->assertDatabaseHas('follow_requests', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);
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

        $this->assertDatabaseHas('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);
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

        $this->assertDatabaseMissing('follow_requests', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user->id,
        ]);
    }
}
