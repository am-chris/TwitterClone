<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FollowTest extends TestCase
{
    public function test_user_can_follow_another_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user2->id . '/follow', [
                'user_id' => $user2->id,
                'current_user_id' => $user1->id,
            ]);

        $this->assertDatabaseHas('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user1->id,
        ]);
    }

    public function test_user_can_unfollow_another_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('DELETE', '/u/' . $user2->id . '/unfollow', [
                'user_id' => $user2->id,
                'current_user_id' => $user1->id,
            ]);

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user2->id,
            'follower_id' => $user1->id,
        ]);
    }

    public function test_user_cant_follow_themselves()
    {
        $user1 = factory(User::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/u/' . $user1->id . '/follow', [
                'user_id' => $user1->id,
                'current_user_id' => $user1->id,
            ]);

        $this->assertDatabaseMissing('follows', [
            'followed_id' => $user1->id,
            'follower_id' => $user1->id,
        ]);
    }
}
