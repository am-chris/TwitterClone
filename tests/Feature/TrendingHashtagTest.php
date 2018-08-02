<?php

namespace Tests\Feature;

use Redis;
use App\User;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingHashtagTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Redis::del('trending_hashtags');
    }

    /** @test */
    public function hashtags_are_added_to_trending_list_when_included_in_a_new_post()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('posts.store'), [
                'content' => 'This is a #cool post',
            ]);

        $this->assertCount(1, Redis::zrangebyscore('trending_hashtags', 0, 1));
    }

    /** @test */
    public function decrement_trending_hashtags_when_a_post_containing_it_is_deleted()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('posts.store'), [
                'content' => 'This is a #cool post',
            ]);

        $this->assertCount(1, Redis::zrangebyscore('trending_hashtags', 0, 1));

        $post = Post::first();

        $this->actingAs($user)
            ->json('DELETE', route('posts.destroy', $post->id));

        $this->assertEmpty(Redis::zrevrangebyscore('trending_hashtags', 0, 2));
    }
}
