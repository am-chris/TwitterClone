<?php

namespace Tests\Feature\User;

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $role = new Role;
        $role->name = 'admin';
        $role->save();
    }

    /** @test */
    public function user_can_change_their_username()
    {
        $user = factory(User::class)->create(['username' => 'JohnDoe']);

        $this->actingAs($user)
            ->json('PUT', route('users.update', $user->id), [
                'name' => $user->name,
                'username' => 'JaneDoe',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'JaneDoe',
        ]);
    }

    /** @test */
    public function user_loses_verified_status_when_they_change_their_username()
    {
        $user = factory(User::class)->create(['username' => 'john', 'verified' => 1]);

        $this->actingAs($user)
            ->json('PUT', route('users.update', $user->id), [
                'name' => $user->name,
                'username' => 'jim',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'jim',
            'verified' => 0,
        ]);
    }

    /** @test */
    public function user_cant_change_their_username_to_a_taken_username()
    {
        $user = factory(User::class)->create(['username' => 'JohnDoe']);
        $user2 = factory(User::class)->create(['username' => 'JaneDoe']);

        $this->actingAs($user)
            ->json('PUT', route('users.update', $user->id), [
                'name' => $user->name,
                'username' => 'JaneDoe',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'JohnDoe',
        ]);
    }

    /** @test */
    public function user_cant_change_another_users_profile()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('PUT', route('users.update', $user2->id), [
                'name' => 'new name',
                'username' => 'test',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'new name',
            'username' => 'test',
        ]);
    }

    /** @test */
    public function admin_can_update_a_users_profile()
    {
        $user = factory(User::class)->create(['username' => 'jim']);
        $admin = factory(User::class)->create()->attachRole('admin');
        
        $this->actingAs($admin)
            ->json('PUT', route('users.update', $user->id), [
                'name' => $user->name,
                'username' => 'test',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'test',
        ]);
    }

    /** @test */
    public function when_an_admin_changes_a_users_profile_the_user_doesnt_lose_verified_status()
    {
        $user = factory(User::class)->create(['verified' => 1]);
        $admin = factory(User::class)->create()->attachRole('admin');

        $this->actingAs($admin)
            ->json('PUT', route('users.update', $user->id), [
                'name' => $user->name,
                'username' => 'test',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'verified' => 1,
        ]);
    }

    /** @test */
    public function symbols_are_stripped_when_changing_username()
    {
        $user = factory(User::class)->create(['username' => 'JohnDoe']);

        $this->actingAs($user)
            ->json('PUT', route('users.update', $user->id), [
                'name' => $user->name,
                'username' => 'JaneDoe~!@#$%^&*(){}|,.?',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'JaneDoe',
        ]);
    }
}
