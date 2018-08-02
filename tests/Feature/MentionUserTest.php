<?php

namespace Tests\Feature;

use App\Models\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $user = factory(User::class)->create(['username' => 'JohnDoe']);
        $user2 = factory(User::class)->create(['username' => 'JaneDoe']);
        $post = factory(Post::class)->create();

        $this->actingAs($user)
            ->json('POST', route('posts.store'), [
                'post_id' => $post->id,
                'user_id' => $user->id,
                'content' => '@JaneDoe look at this.',
            ]);

        $this->assertCount(1, $user2->notifications);
    }
}
