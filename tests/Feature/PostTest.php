<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $admin = new Role;
        $admin->name = 'admin';
        $admin->save();
    }

    /** @test */
    public function user_can_create_post()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('posts.store'), [
                'user_id' => $user->id,
                'content' => 'Some content',
            ]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_can_delete_their_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)
            ->json('DELETE', route('posts.destroy', $post->id));

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_cant_delete_another_users_post()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user2->id]);

        $this->actingAs($user)
            ->json('DELETE', route('posts.destroy', $post->id));

        $this->assertDatabaseHas('posts', [
            'user_id' => $user2->id
        ]);
    }

    /** @test */
    public function admin_can_delete_another_users_post()
    {
        $admin = factory(User::class)->create()->attachRole('admin');
        $user2 = factory(User::class)->create();
        $post = factory(Post::class)->create(['user_id' => $user2->id]);

        $this->actingAs($admin)
            ->json('DELETE', route('posts.destroy', $post->id));

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user2->id
        ]);
    }
}
