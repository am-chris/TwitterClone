<?php

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 5000) as $index) {
            $timestamp = Carbon::now();
            $posts[] = [
                'user_id'      => rand(1, 50),
                'content'      => $faker->paragraph,
                'created_at'   => $timestamp,
            ];
        }

        Post::insert($posts);
    }
}
