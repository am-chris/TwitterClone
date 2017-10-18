<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\Post;

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
        $limit = 400;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('posts')->insert([
                'user_id' => rand(1, 20),
                'content' => $faker->paragraph,
                'created_at' => Carbon::now()->subDays(rand(1, 400)),
            ]);
        }
    }
}
