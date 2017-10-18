<?php

namespace Tests\Feature;

use App\Models\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_create_post()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', '/p', [
                'user_id' => $user->id,
                'content' => 'Some content',
            ]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_delete_their_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)
            ->json('DELETE', '/p/' . $post->id);

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id
        ]);
    }

    public function test_user_cant_delete_another_users_post()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user2->id]);

        $this->actingAs($user)
            ->json('DELETE', '/p/' . $post->id);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user2->id
        ]);
    }

    public function test_admin_can_delete_another_users_post()
    {
        $user = factory(User::class)->create(['admin' => 1]);
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user2->id]);

        $this->actingAs($user)
            ->json('DELETE', '/p/' . $post->id);

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id
        ]);
    }
}
