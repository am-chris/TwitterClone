<?php

namespace Tests\Feature\User;

use Storage;
use App\Role;
use App\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoTest extends TestCase
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
    public function only_a_logged_in_user_can_upload_a_photo()
    {
        $user = factory(User::class)->create();

        $this->json('POST', route('users.photos.store', $user->id))
            ->assertStatus(401);
    }

    /** @test */
    public function uploaded_photo_must_be_valid()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => 'not an image',
            ])->assertStatus(422);
    }

    /** @test */
    public function user_can_upload_a_photo_to_their_profile()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 400, 400),
            ]);

        $user = User::findOrFail($user->id);

        $this->assertEquals('users/photos/' . $file->hashName(), $user->photo_url);

        Storage::assertExists('users/photos/' . $file->hashName());
    }

    /** @test */
    public function previous_photo_gets_destroyed_when_a_photo_is_uploaded()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 400, 400),
            ]);

        $originalPhotoHashName = $file->hashName();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $newCoverPhoto = UploadedFile::fake()->image('photo2.jpg', 400, 400),
            ]);

        Storage::assertMissing('users/photos/' . $originalPhotoHashName);

        Storage::assertExists('users/photos/' . $newCoverPhoto->hashName());
    }

    /** @test */
    public function user_cant_upload_photo_greater_than_maximum_file_size()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg')->size(1000),
            ]);

        Storage::assertMissing('users/photos/' . $file->hashName());
    }

    /** @test */
    public function user_cant_upload_photo_bigger_than_max_dimensions()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        // Assert image greater than max width is not uploaded
        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 2000),
            ]);

        Storage::assertMissing('users/photos/' . $file->hashName());

        // Assert image greater than max height is not uploaded
        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 400, 800),
            ]);

        Storage::assertMissing('users/photos/' . $file->hashName());
    }

    /** @test */
    public function user_can_destroy_their_photo()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 400, 400),
            ]);

        Storage::assertExists('users/photos/' . $file->hashName());

        $this->actingAs($user)
            ->json('DELETE', route('users.photos.destroy', $user->id));

        Storage::assertMissing('users/photos/' . $file->hashName());
    }

    /** @test */
    public function user_cant_destroy_another_users_photo()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        
        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 400, 400),
            ]);

        Storage::assertExists('users/photos/' . $file->hashName());

        $this->actingAs($user2)
            ->json('DELETE', route('users.photos.destroy', $user->id));

        Storage::assertExists('users/photos/' . $file->hashName());
    }

    /** @test */
    public function admin_can_destroy_another_users_photo()
    {
        $user = factory(User::class)->create();
        $admin = factory(User::class)->create()->attachRole('admin');

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('users.photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('photo.jpg', 400, 400),
            ]);

        Storage::assertExists('users/photos/' . $file->hashName());

        $this->actingAs($admin)
            ->json('DELETE', route('users.photos.destroy', $user->id));

        Storage::assertMissing('users/photos/' . $file->hashName());
    }
}
