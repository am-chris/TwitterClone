<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_block_another_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user2->id . '/block', [
                'current_user_id' => $user1->id,
                'user_id' => $user2->id,
            ]);

        $this->assertDatabaseHas('blocked_users', [
            'blocker_id' => $user1->id,
            'blocked_id' => $user2->id,
        ]);
    }

    public function test_user_is_unfollowed_when_blocked()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user2->id . '/follow', [
                'followed_id' => $user2->id,
                'follower_id' => $user1->id,
            ]);

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user2->id . '/block', [
                'current_user_id' => $user1->id,
                'user_id' => $user2->id,
            ]);

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user1->id,
        ]);
    }

    public function test_user_can_unblock_a_blocked_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user2->id . '/block', [
                'current_user_id' => $user1->id,
                'user_id' => $user2->id,
            ]);

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user2->id . '/unblock', [
                'current_user_id' => $user1->id,
                'user_id' => $user2->id,
            ]);

        $this->assertDatabaseMissing('blocked_users', [
            'blocker_id' => $user1->id,
            'blocked_id' => $user2->id,
        ]);
    }

    public function test_user_cant_block_themself()
    {
        $user1 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user1->id . '/block', [
                'current_user_id' => $user1->id,
                'user_id' => $user1->id,
            ]);

        $this->assertDatabaseMissing('blocked_users', [
            'blocker_id' => $user1->id,
            'blocked_id' => $user1->id,
        ]);
    }
}
