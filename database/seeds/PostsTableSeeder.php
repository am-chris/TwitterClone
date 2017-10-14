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
        $limit = 500;
        $date = Carbon::create(2016, 5, 28, 0, 0, 0); // Used to get random date

        for ($i = 0; $i < $limit; $i++) {
            $date = $date->addMinutes(rand(30, 120)); // Used to get random date

            DB::table('posts')->insert([ //,
                'user_id' => rand(1, 20),
                'content' => $faker->paragraph,
                'created_at' => $date->addDays(1, 450),
            ]);
        }
    }
}
