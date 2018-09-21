<?php

namespace Tests\Feature\Post;

use App\User;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Post\Share;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::flushall();
    }

    /** @test */
    public function user_can_share_another_users_post()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user2)
            ->json('POST', route('posts.shares.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user2->id,
            ]);

        $this->assertCount(1, $post->shares());
    }

    /** @test */
    public function user_can_share_their_own_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('POST', route('posts.shares.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->assertCount(1, $post->shares());
    }

    /** @test */
    public function user_can_unshare_a_shared_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('POST', route('posts.shares.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->assertCount(1, $post->shares());

        $this->actingAs($user)
            ->json('DELETE', route('posts.shares.destroy', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->assertCount(0, $post->shares());
    }
}
