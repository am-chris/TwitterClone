<?php

namespace Tests\Feature\Post;

use App\User;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Post\Like;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::flushall();
    }

    /** @test */
    public function user_can_like_another_users_post()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user2)
            ->json('POST', route('posts.likes.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user2->id,
            ]);

        $this->assertCount(1, $post->likes());
    }

    /** @test */
    public function user_can_like_their_own_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('POST', route('posts.likes.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->assertCount(1, $post->likes());
    }

    /** @test */
    public function user_can_unlike_a_liked_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('POST', route('posts.likes.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->assertCount(1, $post->likes());

        $this->actingAs($user)
            ->json('DELETE', route('posts.likes.destroy', $post->id));

        $this->assertCount(0, $post->likes());
    }

    /** @test */
    public function user_can_only_like_a_post_once()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->json('POST', route('posts.likes.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->actingAs($user)
            ->json('POST', route('posts.likes.store', $post->id), [
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

        $this->assertCount(1, $post->likes());
    }
}
