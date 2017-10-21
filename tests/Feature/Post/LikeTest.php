<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\Post\Like;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_another_users_post()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user2)
            ->json('POST', '/p/' . $post->id . '/like', [
                'post_id' => $post->id,
                'user_id' => $user2->id,
            ]);

        $this->assertDatabaseHas('post_likes', [
            'post_id' => $post->id,
        ]);
    }

    public function test_user_can_like_their_own_post()
    {
        $user1 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user1)
            ->json('POST', '/p/' . $post->id . '/like', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
            ]);

        $this->assertDatabaseHas('post_likes', [
            'post_id' => $post->id,
        ]);
    }

    public function test_user_can_unlike_a_liked_post()
    {
        $user1 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user1->id]);

        $this->actingAs($user1)
            ->json('POST', '/p/' . $post->id . '/like', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
            ]);

        $this->actingAs($user1)
            ->json('POST', '/p/' . $post->id . '/unlike', [
                'post_id' => $post->id,
                'user_id' => $user1->id,
            ]);

        $this->assertDatabaseMissing('post_likes', [
            'post_id' => $post->id,
        ]);
    }
}
