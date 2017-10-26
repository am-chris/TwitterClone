<?php

namespace Tests\Feature;

use App\Models\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentioned_users_in_a_reply_are_notified()
    {
        $user1 = factory(User::class)->create(['username' => 'JohnDoe']);
        $user2 = factory(User::class)->create(['username' => 'JaneDoe']);
        $post = factory(Post::class)->create();

        $this->actingAs($user1)
            ->json('POST', '/p', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
                'content' => '@JaneDoe look at this.',
            ]);

        $this->assertCount(1, $user2->notifications);
    }
}
