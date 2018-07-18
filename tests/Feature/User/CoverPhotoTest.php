<?php

namespace Tests\Feature\User;

use App\Role;
use App\User;
use Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoverPhotoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_a_logged_in_user_can_upload_a_cover_photo()
    {
        $user = factory(User::class)->create();

        $this->json('POST', route('api.users.cover_photos.store', $user->id))
            ->assertStatus(401);
    }
    
    /** @test */
    public function uploaded_cover_photo_must_be_valid()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => 'not an image',
            ])->assertStatus(422);
    }

    /** @test */
    public function user_can_upload_cover_photo_to_their_profile()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('cover_photo.jpg'),
            ]);

        $user = User::findOrFail($user->id);

        $this->assertEquals('users/cover_photos/' . $file->hashName(), $user->cover_photo_url);

        Storage::assertExists('users/cover_photos/' . $file->hashName());
    }

    /** @test */
    public function previous_cover_photo_gets_destroyed_when_a_cover_photo_is_uploaded()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('cover_photo.jpg'),
            ]);

        $originalCoverPhotoHashName = $file->hashName();

        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $newCoverPhoto = UploadedFile::fake()->image('cover_photo2.jpg'),
            ]);

        Storage::assertMissing('users/cover_photos/' . $originalCoverPhotoHashName);

        Storage::assertExists('users/cover_photos/' . $newCoverPhoto->hashName());
    }

    /** @test */
    public function user_cant_upload_cover_photo_greater_than_maximum_file_size()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('cover_photo.jpg')->size(5000),
            ]);

        Storage::assertMissing('users/cover_photos/' . $file->hashName());
    }

    /** @test */
    public function user_cant_upload_cover_photo_bigger_than_max_dimensions()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        // Assert image greater than max width is not uploaded
        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('cover_photo.jpg', 2000),
            ]);

        Storage::assertMissing('users/cover_photos/' . $file->hashName());

        // Assert image greater than max height is not uploaded
        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('cover_photo.jpg', 1400, 600),
            ]);

        Storage::assertMissing('users/cover_photos/' . $file->hashName());
    }

    /** @test */
    public function user_can_destroy_their_cover_photo()
    {
        $user = factory(User::class)->create();

        Storage::fake();

        $this->actingAs($user)
            ->json('POST', route('api.users.cover_photos.store', $user->id), [
                'file' => $file = UploadedFile::fake()->image('cover_photo.jpg'),
            ]);

        Storage::assertExists('users/cover_photos/' . $file->hashName());

        $this->actingAs($user)
            ->json('DELETE', route('api.users.cover_photos.destroy', $user->id));

        Storage::assertMissing('users/cover_photos/' . $file->hashName());
    }
}
