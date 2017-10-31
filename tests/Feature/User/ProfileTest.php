<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_their_username()
    {
        $user1 = factory(User::class)->create(['username' => 'JohnDoe']);

        $this->actingAs($user1)
            ->json('PUT', $user1->username, [
                'name' => $user1->name,
                'username' => 'JaneDoe',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'JaneDoe',
        ]);
    }

    public function test_user_cant_change_their_username_to_a_taken_username()
    {
        $user1 = factory(User::class)->create(['username' => 'JohnDoe']);
        $user2 = factory(User::class)->create(['username' => 'JaneDoe']);

        $this->actingAs($user1)
            ->json('PUT', $user1->username, [
                'name' => $user1->name,
                'username' => 'JaneDoe',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'JohnDoe',
        ]);
    }

    public function test_symbols_are_stripped_when_changing_username()
    {
        $user1 = factory(User::class)->create(['username' => 'JohnDoe']);

        $this->actingAs($user1)
            ->json('PUT', $user1->username, [
                'name' => $user1->name,
                'username' => 'JaneDoe~!@#$%^&*(){}|,.?',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'JaneDoe',
        ]);
    }
}
