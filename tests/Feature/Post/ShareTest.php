<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\Post\Share;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_share_another_users_post()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user2)
            ->json('POST', '/p/' . $post->id . '/share', [
                'post_id' => $post->id,
                'user_id' => $user2->id,
            ]);

        $this->assertDatabaseHas('post_shares', [
            'post_id' => $post->id,
        ]);
    }

    /** @test */
    public function user_can_share_their_own_post()
    {
        $user1 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user1)
            ->json('POST', '/p/' . $post->id . '/share', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
            ]);

        $this->assertDatabaseHas('post_shares', [
            'post_id' => $post->id,
        ]);
    }

    /** @test */
    public function user_can_unshare_a_shared_post()
    {
        $user1 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user1)
            ->json('POST', '/p/' . $post->id . '/share', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
            ]);

        $this->actingAs($user1)
            ->json('POST', '/p/' . $post->id . '/unshare', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
            ]);

        $this->assertDatabaseMissing('post_shares', [
            'post_id' => $post->id,
        ]);
    }
}
